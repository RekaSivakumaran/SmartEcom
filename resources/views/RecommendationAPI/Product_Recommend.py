from flask import Flask, request, jsonify
from flask_cors import CORS
import pandas as pd
import pickle
import os
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import cosine_similarity
import numpy as np

app = Flask(__name__)
CORS(app, origins=["http://127.0.0.1:8000", "http://localhost:8000"])

# ─────────────────────────────────────────────────────────
# Load saved models
# ─────────────────────────────────────────────────────────
BASE = os.path.join(os.path.dirname(__file__), 'saved_models')

print("Loading models...")
tfidf     = pickle.load(open(os.path.join(BASE, 'tfidf.pkl'),     'rb'))
model_knn = pickle.load(open(os.path.join(BASE, 'model_knn.pkl'), 'rb'))
knn_clf   = pickle.load(open(os.path.join(BASE, 'knn_clf.pkl'),   'rb'))
df_items  = pd.read_csv(os.path.join(BASE, 'df_items.csv'))
print(f"✅ Models loaded! {len(df_items)} products ready.")


# ─────────────────────────────────────────────────────────
# Original recommend endpoint (Online Retail dataset)
# POST /recommend
# Body: { "product_name": "mug", "n": 6 }
# ─────────────────────────────────────────────────────────
def recommend_products(search_term, n=6):
    query_vector = tfidf.transform([search_term])
    distances, indices = model_knn.kneighbors(query_vector, n_neighbors=n)

    results = []
    for i, idx in enumerate(indices.flatten()):
        product    = df_items.iloc[idx]['Description']
        stock_code = str(df_items.iloc[idx]['StockCode']).strip()
        score      = round(float(1 - distances.flatten()[i]), 2)
        results.append({
            "product":    product,
            "stock_code": stock_code,
            "similarity": score
        })
    return results


@app.route('/recommend', methods=['POST'])
def recommend():
    data = request.get_json()

    if not data or 'product_name' not in data:
        return jsonify({"error": "product_name is required"}), 400

    search_term = str(data.get('product_name', '')).strip()
    n           = int(data.get('n', 6))

    if not search_term:
        return jsonify({"error": "product_name cannot be empty"}), 400

    try:
        results = recommend_products(search_term, n)
        return jsonify(results), 200
    except Exception as e:
        return jsonify({"error": str(e)}), 500



@app.route('/recommend-from-db', methods=['POST'])
def recommend_from_db():
    data = request.get_json()

    if not data:
        return jsonify({"error": "No data provided"}), 400

    purchased  = data.get('purchased', [])  
    candidates = data.get('candidates', [])  
    n          = int(data.get('n', 6))

    if not purchased or not candidates:
        return jsonify([]), 200

    try:
        
        all_names = purchased + candidates

        
        vectorizer = TfidfVectorizer(
            analyzer='word',
            ngram_range=(1, 2),  
            stop_words='english',
            lowercase=True
        )
        tfidf_matrix = vectorizer.fit_transform(all_names)

      
        purchased_vectors  = tfidf_matrix[:len(purchased)]
       
        candidate_vectors  = tfidf_matrix[len(purchased):]

       
        similarity_matrix = cosine_similarity(purchased_vectors, candidate_vectors)

        
        max_scores = np.max(similarity_matrix, axis=0)

        
        results = []
        for i, candidate in enumerate(candidates):
            score = round(float(max_scores[i]), 2)
            if score > 0:  
                results.append({
                    "product":    candidate,
                    "similarity": score
                })

        results = sorted(results, key=lambda x: x['similarity'], reverse=True)[:n]

        return jsonify(results), 200

    except Exception as e:
        print(f"Error in /recommend-from-db: {e}")
        return jsonify({"error": str(e)}), 500


# ─────────────────────────────────────────────────────────
# GET /health
# ─────────────────────────────────────────────────────────
@app.route('/health', methods=['GET'])
def health():
    return jsonify({
        "status":   "ok",
        "products": len(df_items)
    }), 200


# ─────────────────────────────────────────────────────────
if __name__ == '__main__':
    print("\n🚀 Flask Recommendation API")
    print("   Running on: http://127.0.0.1:5000")
    print("   POST /recommend          → Online Retail dataset recommend")
    print("   POST /recommend-from-db  → உங்கள் DB products recommend")
    print("   GET  /health             → check status\n")
    app.run(host='127.0.0.1', port=5000, debug=True)