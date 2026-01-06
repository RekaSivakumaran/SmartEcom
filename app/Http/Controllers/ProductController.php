<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductModel;
use App\Models\MainCategoryModel;
use App\Models\SubCategoryModel;
use App\Models\BrandModel;

class ProductController extends Controller
{
   public function index()
    {
        $mainCategories = MainCategoryModel::select('id', 'Maincategoryname')->get();
        $subcategories = SubCategoryModel::select('id','sub_category_name','main_category_id')->where('status', 'active')->get();

        $Brands = BrandModel::select('id','brandname')->get();
        $products = Product::all();


        return view('Admin.product', compact('mainCategories', 'subcategories', 'Brands', 'products'));
    }

}
