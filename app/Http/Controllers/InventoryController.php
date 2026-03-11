<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductModel;
use App\Models\InventoryLogModel;

class InventoryController extends Controller
{
    public function index()
    {
        $products = ProductModel::where('status', 'Active')
            ->orderBy('quantity', 'asc')
            ->get();

        $logs = InventoryLogModel::with('product')
            ->latest()
            ->take(50)
            ->get();

        return view('Admin.inventory', compact('products', 'logs'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type'       => 'required|in:in,out',
            'quantity'   => 'required|integer|min:1',
            'reason'     => 'nullable|string|max:100',
            'note'       => 'nullable|string|max:255',
        ]);

        $product = ProductModel::findOrFail($request->product_id);
        $before  = $product->quantity;

        if ($request->type === 'in') {
            $product->quantity += $request->quantity;
        } else {
            if ($product->quantity < $request->quantity) {
                return back()->with('error', 'Stock இல்லை! Available: ' . $product->quantity);
            }
            $product->quantity -= $request->quantity;
        }

        $product->save();

        InventoryLogModel::create([
            'product_id'      => $product->id,
            'type'            => $request->type,
            'quantity'        => $request->quantity,
            'quantity_before' => $before,
            'quantity_after'  => $product->quantity,
            'reason'          => $request->reason,
            'note'            => $request->note,
        ]);

        return back()->with('success', 'Stock updated successfully!');
    }
}