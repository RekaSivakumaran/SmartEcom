<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategoryModel;
use App\Models\MainCategoryModel;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    public function index()
    {
        $mainCategories = MainCategoryModel::select('id', 'Maincategoryname')->get();
        $subcategories = SubCategoryModel::with('mainCategory')->get();

        return view('Admin.subcategory', compact('subcategories', "mainCategories"));
    }

    public function store(Request $request)
    {
        $request->validate([
                'sub_category_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_categories')->where(function ($query) use ($request) {
                    return $query->where('main_category_id', $request->main_category_id);
                }),
            ],
            'description'       => 'required|string',
            'main_category_id'  => 'required|exists:main_categories,id',
            'status'            => 'required|in:Active,Inactive',
        // 'image'             => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $folderPath = public_path('image/SubCategory');

        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0777, true);
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = str_replace(' ', '_', $request->sub_category_name)
               . '_mc' . $request->main_category_id
               . '.' . $image->getClientOriginalExtension();

            $image->move($folderPath, $imageName);
            $imagePath = 'image/SubCategory/' . $imageName;
            } else {
            return back()->withErrors(['image' => 'Please upload an image']);
        }


    
        SubCategoryModel::create([
        'sub_category_name' => $request->sub_category_name,
        'description'       => $request->description,
        'main_category_id'  => $request->main_category_id,
        'status'            => $request->status,
        'image'             => 'image/SubCategory/'.$imageName,
        ]);

        return redirect()->back()->with('success', 'Sub Category added successfully!');
    }

    public function update(Request $request, $id)
    {
        $subCategory = SubCategoryModel::findOrFail($id);

        $request->validate([
        'sub_category_name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('sub_categories')
                ->where(function ($query) use ($request) {
                    return $query->where('main_category_id', $request->main_category_id);
                })
                ->ignore($id),  
        ],
        'description'       => 'required|string',
        'main_category_id'  => 'required|exists:main_categories,id',
        'status'            => 'required|in:Active,Inactive',
        'image'             => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $folderPath = public_path('image/SubCategory');

            if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, 0777, true);
        }

        $imagePath = $subCategory->image;  

        if ($request->hasFile('image')) {

        
        if ($subCategory->image && File::exists(public_path($subCategory->image))) {
            File::delete(public_path($subCategory->image));
        }

        $image = $request->file('image');
        $imageName = str_replace(' ', '_', $request->sub_category_name)
            . '_mc' . $request->main_category_id
            . '.' . $image->getClientOriginalExtension();

        $image->move($folderPath, $imageName);
        $imagePath = 'image/SubCategory/' . $imageName;
    }

    $subCategory->update([
        'sub_category_name' => $request->sub_category_name,
        'description'       => $request->description,
        'main_category_id'  => $request->main_category_id,
        'status'            => $request->status,
        'image'             => $imagePath,
    ]);

    return redirect()->back()->with('success', 'Sub Category updated successfully!');
}

    public function destroy($id)
    {
        $subCategory = SubCategoryModel::findOrFail($id);

        // Delete image file if exists
        if($subCategory->image && File::exists(public_path($subCategory->image))){
        File::delete(public_path($subCategory->image));
        }

        $subCategory->delete();

        return redirect()->back()->with('success', 'Sub Category deleted successfully!');
    }


//     public function update(Request $request, $id)
// {
//     // Validate input
//     $request->validate([
//         'Maincategoryname' => 'required|string|max:255|unique:main_categories,Maincategoryname,' . $id,
//         'description'      => 'required|string',
//         'status'           => 'required|in:Active,Inactive',
//         'image'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
//     ]);

//     // Find category
//     $category = MainCategoryModel::findOrFail($id);

//     // Update basic fields
//     $category->Maincategoryname = $request->Maincategoryname;
//     $category->description = $request->description;
//     $category->status = $request->status;

//     // Handle image if uploaded
//     if ($request->hasFile('image')) {
//         $folderPath = public_path('image/MainCategory');
//         if (!file_exists($folderPath)) {
//             mkdir($folderPath, 0777, true);
//         }

//         $image = $request->file('image');
//         $imageName = str_replace(' ', '_', $request->Maincategoryname) . '.' . $image->getClientOriginalExtension();
//         $image->move($folderPath, $imageName);

//         $category->imagepath = 'image/MainCategory/' . $imageName;
//     }

//     $category->save();

//     return redirect()->back()->with('success', 'Main Category updated successfully!');
// }

// public function destroy($id)
// {
//     $category = MainCategoryModel::findOrFail($id);

//     if ($category->imagepath && file_exists(public_path($category->imagepath))) {
//         unlink(public_path($category->imagepath));
//     }

//     $category->delete();

//     return redirect()->back()->with('success', 'Main Category deleted successfully!');
// }


}
