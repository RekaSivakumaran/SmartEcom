<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerModel;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = CustomerModel::all(); // Fetch all customers
        return view('admin.customer', compact('customers'));
    }


    public function updateStatus(Request $request, $id)
    {
    $customer = CustomerModel::findOrFail($id);

    $request->validate([
        'status' => 'required|in:active,block'
    ]);

    $customer->status = $request->status;
    $customer->save();

    return redirect()->route('customers.index')->with('success', 'Status updated successfully.');
    }

    
 
}
