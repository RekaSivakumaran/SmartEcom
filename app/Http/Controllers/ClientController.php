<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MainCategoryModel;
use App\Models\ProductModel;
use App\Models\BrandModel;
use App\Models\CustomerModel;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


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



public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'name' => 'required|max:100',
        'email' => 'required|email|unique:customers,email',
        'mobile' => 'required|max:20',
        'password' => 'required|confirmed|min:6',
    ]);

    if ($validator->fails()) {
        // Do NOT redirect, do NOT show error messages
        // You can just log it or silently return
        return redirect()->back(); 
    }

    $customer = CustomerModel::create([
        'name' => $request->name,
        'email' => $request->email,
        'mobile' => $request->mobile,
        'password' => Hash::make($request->password),
        'status' => 'active',
    ]);

    if ($customer) {
        return redirect()->back()->with('success', 'Registration Successful! Please Login.');
    } else {
        return redirect()->back()->with('error', 'Registration Failed!');
    }
    }

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $customer = CustomerModel::where('email', $request->email)
        ->where('status', 'active')
        ->first();

    if (!$customer || !Hash::check($request->password, $customer->password)) {
        return redirect()->back()->with('error', 'Invalid email or password');
    }

    // Store login session
    session([
        'client_id' => $customer->id,
        'client_name' => $customer->name
    ]);

    return redirect()->route('home');
}



public function logout(Request $request)
{  
    Log::info('Session Data Before Logout:', $request->session()->all());
    $request->session()->flush();
    Log::info('Session Data After Logout:', $request->session()->all()); 
    $request->session()->regenerate();
    return redirect()->route('ClientLogin');
}

// public function logout(Request $request)
// { 
//        dd($request->session()->all());
//         $request->session()->flush();

//     return redirect()->route('ClientLogin'); 
// }
     
// public function showDeliveryInfo($productId)
// {
//     $product = ProductModel::findOrFail($productId);  
//     return view('client.DeliveryInfo', compact('product'));
// }

public function showDeliveryInfo($productId, Request $request)
{
    $quantity = $request->quantity ?? 1;

    $product = ProductModel::findOrFail($productId);

    return view('client.DeliveryInfo', compact('product', 'quantity'));
}



public function storeSingle(Request $request)
{
    $validated = $request->validate([

        // Billing Required
        'first_name' => 'required|string|max:100',
        'last_name' => 'required|string|max:100',
        'address1' => 'required|string|max:255',
        'city' => 'required|string|max:100',
        'country' => 'required|string|max:100',
        'postcode' => 'required|string|max:20',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:255',

        // Shipping Only If Checked
        'ship_address1' => 'required_if:ship_different,1|nullable|string|max:255',
        'ship_city' => 'required_if:ship_different,1|nullable|string|max:100',
        'ship_country' => 'required_if:ship_different,1|nullable|string|max:100',
        'ship_zip' => 'required_if:ship_different,1|nullable|string|max:20',
        'ship_phone' => 'required_if:ship_different,1|nullable|string|max:20',
    ]);

    DB::beginTransaction();

    $product = ProductModel::lockForUpdate()->findOrFail($request->product_id);


    if ($product->quantity <= 0) {
            return back()->with('error', 'Product is out of stock.');
        }

         $originalPrice = $product->price;

        if ($product->discount_type == 'rate') {
            $discountValue = $originalPrice * $product->discount_rate / 100;
        } elseif ($product->discount_type == 'amount') {
            $discountValue = $product->discount_amount;
        } else {
            $discountValue = 0;
        }

        $discountValue = min($discountValue, $originalPrice);
        $finalPrice = $originalPrice - $discountValue;


    DB::beginTransaction();

    try {

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = OrderModel::create([
            'name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'email' => $validated['email'],
            'mobile_number' => $validated['phone'],

            'billing_address1' => $validated['address1'],
            'billing_address2' => $request->address2,
            'billing_city' => $validated['city'],
            'billing_country' => $validated['country'],
            'billing_postcode' => $validated['postcode'],

            'shipping_address1' => $request->ship_different ? $validated['ship_address1'] : null,
            'shipping_address2' => $request->ship_different ? $request->ship_address2 : null,
            'shipping_city' => $request->ship_different ? $validated['ship_city'] : null,
            'shipping_country' => $request->ship_different ? $validated['ship_country'] : null,
            'shipping_postcode' => $request->ship_different ? $validated['ship_zip'] : null,

            'status' => 'Pending',
            'payment_status' => 'Pending',
            'total' => $finalPrice
        ]);

         OrderItemModel::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'image_path' => $product->image,
            'quantity' => 1,
            'price' => $finalPrice,
            'total' => $finalPrice,
        ]);

        DB::commit();

        return redirect()->route('home')
            ->with('success', 'Order placed successfully!');

    } catch (\Exception $e) {

        DB::rollback();
        return back()->with('error', 'Something went wrong.');
    }
}

}
