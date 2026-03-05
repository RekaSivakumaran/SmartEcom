import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.neighbors import KNeighborsClassifier, NearestNeighbors
from sklearn.metrics import accuracy_score


df = pd.read_csv("Data/Online_Retail.csv", encoding='ISO-8859-1')
    

# Drop rows without Description
df = df.dropna(subset=['Description'])

# Unique items for recommendation
df_items = df[['StockCode', 'Description']].drop_duplicates(subset=['StockCode'])

# Take a sample of ~100,000 rows for training
df_sample = df.sample(n=min(100000, len(df)), random_state=42)

# -----------------------------
# 2️⃣ TF-IDF Vectorization
# -----------------------------
tfidf = TfidfVectorizer(stop_words='english', max_features=1000)
X = tfidf.fit_transform(df_sample['Description'])
y = df_sample['StockCode']   # multi-class target

# -----------------------------
# 3️⃣ Train/Test Split
# -----------------------------
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# -----------------------------
# 4️⃣ KNN Classification
# -----------------------------
knn_clf = KNeighborsClassifier(n_neighbors=5)
knn_clf.fit(X_train, y_train)

y_train_pred = knn_clf.predict(X_train)
y_test_pred = knn_clf.predict(X_test)

train_acc = accuracy_score(y_train, y_train_pred)
test_acc = accuracy_score(y_test, y_test_pred)

# print("-" * 40)
# print(f"Train Accuracy: {train_acc*100:.2f}%")
# print(f"Test Accuracy: {test_acc*100:.2f}%")
# print("-" * 40)

# -----------------------------
# 5️⃣ Build Recommendation Model
# -----------------------------
# Use TF-IDF without refitting
tfidf_matrix_all = tfidf.transform(df_items['Description'].fillna(''))
model_knn = NearestNeighbors(metric='cosine', algorithm='brute')
model_knn.fit(tfidf_matrix_all)

def recommend_products(search_term, n=5):
    query_vector = tfidf.transform([search_term])
    distances, indices = model_knn.kneighbors(query_vector, n_neighbors=n)
    
    results = []
    for i, idx in enumerate(indices.flatten()):
        product = df_items.iloc[idx]['Description']
        score = 1 - distances.flatten()[i]
        results.append({"product": product, "similarity": round(score, 2)})
    
    return results

# # -----------------------------
# # 6️⃣ Recommendation Function
# # -----------------------------
# def recommend_products(search_term):
#     query_vector = tfidf.transform([search_term])
#     distances, indices = model_knn.kneighbors(query_vector, n_neighbors=5)

#     print(f"\n🔎 Recommendations related to '{search_term}':")
#     for i, idx in enumerate(indices.flatten()):
#         product = df_items.iloc[idx]['Description']
#         score = 1 - distances.flatten()[i]
#         print(f"{i+1}. {product} (Similarity: {score:.2f})")

# # -----------------------------
# # 7️⃣ User Input
# # -----------------------------
# search = input("\nEnter the product name you want to search: ")
# recommend_products(search)