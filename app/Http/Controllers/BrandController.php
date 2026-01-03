<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BrandModel;


class BrandController extends Controller
{

   public function index()
    {
        $brands = BrandModel::all();
        return view('Admin.brand', compact('brands'));  
    }


    // Store new brand
    public function store(Request $request)
    {
        $request->validate([
            'brandname' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        BrandModel::create([
            'brandname' => $request->brandname,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Brand added successfully!');
    }

    // Update existing brand
    public function update(Request $request, $id)
    {
        $request->validate([
            'brandname' => 'required|string|max:255',
            'status' => 'required|in:Active,Inactive',
        ]);

        $brand = BrandModel::findOrFail($id);
        $brand->update([
            'brandname' => $request->brandname,
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Brand updated successfully!');
    }

    // Delete brand
    public function destroy($id)
    {
        $brand = BrandModel::findOrFail($id);
        $brand->delete();

        return redirect()->back()->with('success', 'Brand deleted successfully!');
    }
}
