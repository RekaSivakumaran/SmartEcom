<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MainCategoryModel;
use App\Models\ProductModel;
use App\Models\BrandModel;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function index()
    {
        // $departments = MainCategoryModel::inRandomOrder()->take(6)->get();  
         $departments = MainCategoryModel::where('status', 'Active')
                    ->inRandomOrder()
                    ->take(6)
                    ->get();

         $products = ProductModel::where('status', 'Active')
                ->where(function($query){
                    $query->where('created_at', '>=', Carbon::now()->subDays(7))  
                          ->orWhere('discount_amount', '>', 0)                
                          ->orWhere('discount_rate', '>', 0);
                })
                ->inRandomOrder()    
                ->take(6)            
                ->get();

   
        return view('Client.home', compact('departments', 'products'));
    }

    public function show($id)
    {
        $product = ProductModel::findOrFail($id);

        return view('Client.shopdetails', compact('product'));
    }


    public function showCategories(Request $request)
    { 
        $categories = MainCategoryModel::where('status', 'Active') 
        ->whereHas('subCategories', fn ($q) => $q->where('status', 'Active')) 
        ->with(['subCategories' => fn ($q) => $q->where('status', 'Active')]) ->get();

        $brands = BrandModel::whereHas('products', function ($q) 
        { $q->where('status', 'Active'); })->get();

        $products = ProductModel::where('status', 'Active') ->when($request->brand, fn ($q) => $q->where('brand_id', $request->brand) ) 
        ->get(); 

        return view('Client.Item', compact('categories', 'brands', 'products'));
    }

    


//    public function showCategories()
//    {
//    $categories = MainCategoryModel::where('status', 'Active')
//     ->whereHas('subCategories', function ($q) {
//         $q->where('status', 'Active');
//     })
//     ->with(['subCategories' => function ($q) {
//         $q->where('status', 'Active');
//     }])
//     ->get();


//     $products = ProductModel::where('status', 'Active')->get();

//     return view('Client.Item', compact('categories', 'products'));
// }

// public function productsBySubCategory($id)
// {
//     $categories = MainCategoryModel::with([
//         'subCategories' => function ($q) {
//             $q->where('status', 'Active');
//         }
//     ])->where('status', 'Active')->get();

//     $products = ProductModel::where('sub_category_id', $id)
//         ->where('status', 'Active')
//         ->get();

//     return view('Client.Item', compact('categories', 'products'));
// }

public function productsBySubCategory(Request $request, $id)
{
    $categories = MainCategoryModel::where('status', 'Active')
        ->with(['subCategories' => fn ($q) => $q->where('status', 'Active')])
        ->get();

    // Brands that have products in this sub category
    $brands = BrandModel::whereHas('products', function ($q) use ($id) {
        $q->where('status', 'Active')
          ->where('sub_category_id', $id);
    })->get();

    $products = ProductModel::where('sub_category_id', $id)
        ->where('status', 'Active')
        ->when($request->brand, fn ($q) =>
            $q->where('brand_id', $request->brand)
        )
        ->get();

    return view('Client.Item', compact('categories', 'brands', 'products'));
}


     

}
