<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubCategoryModel;
use App\Models\MainCategoryModel;
use Illuminate\Support\Facades\File;

class SubCategoryController extends Controller
{
    public function index()
    {
        $mainCategories = MainCategoryModel::select('id', 'Maincategoryname')->get();
        $subcategories = SubCategoryModel::with('mainCategory')->get();

        return view('Admin.subcategory', compact('subcategories', "mainCategories"));
    }

//     public function store(Request $request)
//     {
//     $request->validate([
//         'Maincategoryname' => 'required|string|max:255|unique:main_categories,Maincategoryname',
//         'description'      => 'required|string',
//         'status'           => 'required|in:Active,Inactive',
//         'image'            => 'required|image|mimes:jpg,jpeg,png|max:2048',
//     ]);

//     $folderPath = public_path('image/MainCategory');

//     if (!File::exists($folderPath)) {
//         File::makeDirectory($folderPath, 0777, true);
//     }

//     $imagePath = null;

//     if ($request->hasFile('image')) {
//         $image = $request->file('image');
//         $imageName = str_replace(' ', '_', $request->Maincategoryname)
//                    . '.' . $image->getClientOriginalExtension();
//         $image->move($folderPath, $imageName);
//         $imagePath = 'image/MainCategory/' . $imageName;
//     }

//     MainCategoryModel::create([
//         'Maincategoryname' => $request->Maincategoryname,
//         'description'      => $request->description,
//         'status'           => $request->status,
//         'imagepath'        => $imagePath,
//     ]);

//     return back()->with('success', 'Main Category created successfully');
//     }

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
