<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\orderModel;
use App\Models\orderItemModel;

class OrderController extends Controller
{
    public function index()
    {
         $orders = OrderModel::with(['items.product'])->get();

        return view('Admin.order', compact('orders'));

        //return view('Admin.order', compact('products','orders'));
    }

     public function update(Request $request, $id)
    {
        try {
           
            $validated = $request->validate([
                'status' => 'required|in:Pending,Processing,Completed,Cancelled',
                'payment_status' => 'required|in:Pending,Paid,Failed',
            ]);

            
            $order = orderModel::findOrFail($id);

          
            $order->status = $validated['status'];
            $order->payment_status = $validated['payment_status'];
            $order->save();

           return redirect()->back()->with('success', 'Order updated successfully!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update order: ' . $e->getMessage());
        }
    }
}
