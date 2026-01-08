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
     $products = ProductModel::with(['mainCategory', 'subCategory', 'brand'])->get();


        return view('Admin.product', compact('mainCategories', 'subcategories', 'Brands', 'products'));
    }

    public function store(Request $request)
    {
    $request->validate([
        'item_name' => 'required|string|max:255|unique:products,name',      
        'main_category_id' => 'required|exists:main_categories,id',
        'sub_category_id' => 'required|exists:sub_categories,id',
        'brand_id' => 'required|exists:brands,id',
        'price' => 'required|numeric|min:0.01',
        'quantity' => 'required|integer|min:0',
        'discount_type' => 'required|in:none,rate,amount',
        'discount_rate' => 'nullable|numeric|min:0|max:100',
        'discount_amount' => 'nullable|numeric|min:0',
        'status' => 'required|in:Active,Inactive',
        // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $product = new ProductModel();
    $product->name = $request->item_name;
    $product->main_category_id = $request->main_category_id;
    $product->sub_category_id = $request->sub_category_id;
    $product->brand_id = $request->brand_id;
    $product->price = $request->price;
    $product->quantity = $request->quantity;
    $product->discount_type = $request->discount_type;
    $product->discount_rate = $request->discount_rate ?? 0;
    $product->discount_amount = $request->discount_amount ?? 0;
    $product->description = $request->description;   
    $product->status = $request->status;




    // Handle Image Upload
    if($request->hasFile('image')){
        $file = $request->file('image');

        // Make folder if not exists
        $folderPath = public_path('image/Product');
        if(!file_exists($folderPath)){
            mkdir($folderPath, 0755, true);
        }

        // Clean item name for filename
        $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $request->item_name);
        $extension = $file->getClientOriginalExtension();
        $filename = $safeName . '.' . $extension;

        // Move file to public/image/Product
        $file->move($folderPath, $filename);

        // Save path in DB
        $product->image = 'image/Product/' . $filename;
    }

    $product->save();

    return redirect()->back()->with('success', 'Product added successfully!');
}

public function update(Request $request, $id)
{
    // Find the product
    $product = ProductModel::findOrFail($id);

    // Validate the request
    $request->validate([
        'item_name' => 'required|string|max:255|unique:products,name,' . $product->id,
        'main_category_id' => 'required|exists:main_categories,id',
        'sub_category_id' => 'required|exists:sub_categories,id',
        'brand_id' => 'required|exists:brands,id',
        'price' => 'required|numeric|min:0.01',
        'quantity' => 'required|integer|min:0',
        'discount_type' => 'required|in:none,rate,amount',
        'discount_rate' => 'nullable|numeric|min:0|max:100',
        'discount_amount' => 'nullable|numeric|min:0',
        'status' => 'required|in:Active,Inactive',
        // 'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Update product fields
    $product->name = $request->item_name;
    $product->main_category_id = $request->main_category_id;
    $product->sub_category_id = $request->sub_category_id;
    $product->brand_id = $request->brand_id;
    $product->price = $request->price;
    $product->quantity = $request->quantity;
    $product->discount_type = $request->discount_type;
    $product->discount_rate = $request->discount_rate ?? 0;
    $product->discount_amount = $request->discount_amount ?? 0;
    $product->description = $request->description;   
    $product->status = $request->status;

    // Handle Image Upload (optional)
    if ($request->hasFile('image')) {
        $file = $request->file('image');

        // Make folder if not exists
        $folderPath = public_path('image/Product');
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0755, true);
        }

        // Clean item name for filename
        $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $request->item_name);
        $extension = $file->getClientOriginalExtension();
        $filename = $safeName . '.' . $extension;

        // Move file to public/image/Product
        $file->move($folderPath, $filename);

        // Delete old image if exists
        if ($product->image && file_exists(public_path($product->image))) {
            unlink(public_path($product->image));
        }

        // Save new path
        $product->image = 'image/Product/' . $filename;
    }

    $product->save();

    return redirect()->back()->with('success', 'Product updated successfully!');
}

public function destroy(ProductModel $product)
{
    
    if($product->image && file_exists(public_path($product->image))){
        unlink(public_path($product->image));
    }

    $product->delete();

    return redirect()->back()->with('success', 'Product deleted successfully!');
}



}
