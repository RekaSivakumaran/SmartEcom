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
}
