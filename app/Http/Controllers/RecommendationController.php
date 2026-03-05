<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

    public function showRecommendations($product_name)
{
    // Python API call 
    $response = Http::post('http://127.0.0.1:5000/recommend', [
        'product_name' => $product_name,
        'n' => 5
    ]);

    $recommendedProducts = $response->json(); // [{"product": "...", "similarity": 0.9}, ...]

    // DB
    $products = collect($recommendedProducts)->map(function($item){
        return \App\Models\ProductModel::where('name', $item['product'])->first();
    })->filter(); // null values remove

    return view('recommendations', compact('products'));
}
}
