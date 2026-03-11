<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ProductModel;
use App\Models\orderModel;        
use App\Models\orderItemModel;


class RecommendationController extends Controller
{
    // public function getRecommendations(Request $request)
    // {
       
    //     $response = Http::post('http://127.0.0.1:8000/recommend', [
    //         'product_name' => $request->product_name,
    //         'n' => 5
    //     ]);

      
    //     return $response->json();
    // }

// public function showRecommendations($product_name)
// {
//     // Python API call 
//     $response = Http::post('http://127.0.0.1:5000/recommend', [
//         'product_name' => $product_name,
//         'n' => 5
//     ]);

//     $recommendedProducts = $response->json(); // [{"product": "...", "similarity": 0.9}, ...]

//     // DB
//     $products = collect($recommendedProducts)->map(function($item){
//         return \App\Models\ProductModel::where('name', $item['product'])->first();
//     })->filter(); // null values remove

//     return view('recommendations', compact('products'));
// }



 private string $apiUrl = 'http://127.0.0.1:5000';

   
    public function getPurchasedRecommendations()
    {
        if (!session()->has('client_id')) {
            return response()->json([], 200);
        }

        $clientId = session('client_id');

        try {
            $allOrderIds = orderModel::where('customer_id', $clientId)
                ->pluck('id');

            if ($allOrderIds->isEmpty()) {
                return response()->json([], 200);
            }

            
            $purchasedItems = orderItemModel::whereIn('order_id', $allOrderIds)
                ->with('product')
                ->get();

            $purchasedProductIds = $purchasedItems->pluck('product_id')->unique()->toArray();
            $purchasedNames      = $purchasedItems->pluck('product.name')->filter()->unique()->values()->toArray();

            if (empty($purchasedNames)) {
                return response()->json([], 200);
            }

           
            $candidateProducts = ProductModel::whereNotIn('id', $purchasedProductIds)->get();

            if ($candidateProducts->isEmpty()) {
                return response()->json([], 200);
            }

            $candidateNames = $candidateProducts->pluck('name')->toArray();

            
            $response = Http::timeout(15)->post("{$this->apiUrl}/recommend-from-db", [
                'purchased'  => $purchasedNames,
                'candidates' => $candidateNames,
                'n'          => 6,
            ]);

            if ($response->failed()) {
                Log::warning('Flask recommend-from-db failed', ['status' => $response->status()]);
                return response()->json([], 200);
            }

            $flaskResults = $response->json();

            if (empty($flaskResults)) {
                return response()->json([], 200);
            }

            $recommendations = collect($flaskResults)->map(function ($item) use ($candidateProducts) {
                $name = trim($item['product'] ?? '');

                $product = $candidateProducts->firstWhere('name', $name);

                if (!$product) {
                    $product = $candidateProducts->first(function ($p) use ($name) {
                        return stripos($p->name, $name) !== false ||
                               stripos($name, $p->name) !== false;
                    });
                }

                if (!$product) return null;

                // Price calculation
                if ($product->discount_rate > 0) {
                    $finalPrice  = $product->price - ($product->price * $product->discount_rate / 100);
                    $displayRate = $product->discount_rate;
                } elseif ($product->discount_amount > 0) {
                    $finalPrice  = $product->price - $product->discount_amount;
                    $displayRate = ($product->discount_amount / $product->price) * 100;
                } else {
                    $finalPrice  = $product->price;
                    $displayRate = 0;
                }

                return [
                    'id'           => $product->id,
                    'name'         => $product->name,
                    'image'        => asset($product->image),
                    'price'        => $product->price,
                    'final_price'  => round($finalPrice, 2),
                    'display_rate' => round($displayRate, 0),
                    'similarity'   => $item['similarity'],
                    'detail_url'   => route('Client.shopdetails', $product->id),
                ];
            })->filter()->values();

            if ($recommendations->isEmpty()) {
                $recommendations = $candidateProducts->take(6)->map(function ($p) {
                    if ($p->discount_rate > 0) {
                        $finalPrice  = $p->price - ($p->price * $p->discount_rate / 100);
                        $displayRate = $p->discount_rate;
                    } elseif ($p->discount_amount > 0) {
                        $finalPrice  = $p->price - $p->discount_amount;
                        $displayRate = ($p->discount_amount / $p->price) * 100;
                    } else {
                        $finalPrice  = $p->price;
                        $displayRate = 0;
                    }
                    return [
                        'id'           => $p->id,
                        'name'         => $p->name,
                        'image'        => asset($p->image),
                        'price'        => $p->price,
                        'final_price'  => round($finalPrice, 2),
                        'display_rate' => round($displayRate, 0),
                        'similarity'   => 0.50,
                        'detail_url'   => route('Client.shopdetails', $p->id),
                    ];
                })->values();
            }

            return response()->json([
                'recommendations' => $recommendations,
                'based_on'        => implode(', ', $purchasedNames),
            ], 200);

        } catch (\Exception $e) {
            Log::error('getPurchasedRecommendations error: ' . $e->getMessage());
            return response()->json([], 200);
        }
    }

     
    public function getRecommendationsJson(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|min:1',
            'n'            => 'nullable|integer|min:1|max:20',
        ]);

        $productName = trim($request->input('product_name'));
        $n           = (int) $request->input('n', 6);

        try {
            $candidates = ProductModel::where('name', '!=', $productName)->get();
            $candidateNames = $candidates->pluck('name')->toArray();

            $response = Http::timeout(15)->post("{$this->apiUrl}/recommend-from-db", [
                'purchased'  => [$productName],
                'candidates' => $candidateNames,
                'n'          => $n,
            ]);

            if ($response->failed()) {
                return response()->json([], 200);
            }

            $flaskResults = $response->json();

            $matched = collect($flaskResults)->map(function ($item) use ($candidates) {
                $name    = trim($item['product'] ?? '');
                $product = $candidates->firstWhere('name', $name);

                if (!$product) return null;

                if ($product->discount_rate > 0) {
                    $finalPrice  = $product->price - ($product->price * $product->discount_rate / 100);
                    $displayRate = $product->discount_rate;
                } elseif ($product->discount_amount > 0) {
                    $finalPrice  = $product->price - $product->discount_amount;
                    $displayRate = ($product->discount_amount / $product->price) * 100;
                } else {
                    $finalPrice  = $product->price;
                    $displayRate = 0;
                }

                return [
                    'id'           => $product->id,
                    'name'         => $product->name,
                    'image'        => asset($product->image),
                    'price'        => $product->price,
                    'final_price'  => round($finalPrice, 2),
                    'display_rate' => round($displayRate, 0),
                    'similarity'   => $item['similarity'],
                    'detail_url'   => route('Client.shopdetails', $product->id),
                ];
            })->filter()->values();

            return response()->json($matched, 200);

        } catch (\Exception $e) {
            Log::error('RecommendationController error: ' . $e->getMessage());
            return response()->json([], 200);
        }
    }

    
    // Health check
    // Route: GET /recommendations/health
     
    public function healthCheck()
    {
        try {
            $response = Http::timeout(5)->get("{$this->apiUrl}/health");

            if ($response->successful()) {
                return response()->json([
                    'status'   => 'online',
                    'products' => $response->json('products'),
                ], 200);
            }

            return response()->json(['status' => 'error'], 503);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'offline',
                'error'  => $e->getMessage(),
            ], 503);
        }
    }
}
