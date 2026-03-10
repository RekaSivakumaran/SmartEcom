<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MainCategoryModel;
use App\Models\ProductModel;
use App\Models\BrandModel;
use App\Models\CustomerModel;
use App\Models\orderModel;
use App\Models\orderItemModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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

    // public function show($id)
    // {
    //     $product = ProductModel::findOrFail($id);

    //     return view('Client.shopdetails', compact('product'));
    // }

    public function show($id)
{
    $product = ProductModel::findOrFail($id);

    // எல்லா மற்ற products candidates
    $allOtherProducts = ProductModel::where('id', '!=', $id)
        ->where('status', 'Active')
        ->get();

    $featuredProducts = collect();

    try {
        $candidateNames = $allOtherProducts->pluck('name')->toArray();

        $response = \Illuminate\Support\Facades\Http::timeout(10)
            ->post('http://127.0.0.1:5000/recommend-from-db', [
                'purchased'  => [$product->name],
                'candidates' => $candidateNames,
                'n'          => 4,
            ]);

        if ($response->successful()) {
            $flaskResults = collect($response->json());

            $featuredProducts = $flaskResults->map(function ($item) use ($allOtherProducts) {
                return $allOtherProducts->firstWhere('name', $item['product']);
            })->filter()->values();
        }
    } catch (\Exception $e) {
        
    }

   
    if ($featuredProducts->isEmpty()) {
        $featuredProducts = ProductModel::where('id', '!=', $id)
            ->where('status', 'Active')
            ->inRandomOrder()
            ->take(4)
            ->get();
    }

    return view('Client.shopdetails', compact('product', 'featuredProducts'));
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

public function showDeliveryInfo(Request $request, $productId = null)
{
if ($productId) {
        // Buy Now: single product
        $product = ProductModel::findOrFail($productId);
        $quantity = $request->quantity ?? 1;

        $products = [
            [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'discount_type' => $product->discount_type,
                'discount_rate' => $product->discount_rate,
                'discount_amount' => $product->discount_amount,
                'quantity' => $quantity,
            ]
        ];
    } else {
        // $products = json_decode($request->cartData ?? '[]', true);

        // if(empty($products)) {
        //     return redirect()->route('products.all')->with('error', 'Your cart is empty!');
        //     }
        //$products = session()->get('cart', []);




        $cart = json_decode($request->cartData ?? '[]', true);

    if(empty($cart)) {
        return redirect()->route('products.all')->with('error', 'Your cart is empty!');
    }

    $products = [];
    $subTotal = 0;

    foreach($cart as $item) {
        $product = ProductModel::find($item['id']);
        if(!$product) continue;

        $quantity = $item['quantity'] ?? 1;
         $originalPrice = $product->price;

        // // Discount logic from DB
        // if ($product->discount_type == 'rate') {
        //     $discountValue = ($originalPrice * $product->discount_rate) / 100;
        // } elseif ($product->discount_type == 'amount') {
        //     $discountValue = $product->discount_amount;
        // } else {
        //     $discountValue = 0;
        // }

        // $discountValue = min($discountValue, $originalPrice);
        // $discountedPrice = $originalPrice - $discountValue;
        // $total = $discountedPrice * $quantity;

        // $subTotal += $total;

        $products[] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $originalPrice,
            'discount_type' => $product->discount_type,
            'discount_rate' => $product->discount_rate,
            'discount_amount' => $product->discount_amount,
            'quantity' => $quantity,
            // 'discounted_price' => $discountedPrice,
            // 'total' => $total,
            'img' => $product->img ?? null,
        ];




        
    }
    }

      $billing = session('billing');

    if ($billing == null) {
        // Billing session இல்லை → First time page
        return view('client.DeliveryInfo', compact('products'));
    } else {
        // Billing session உள்ளது → Summary page
        return view('client.DeliveryInfo2', compact('products'));
    }

    //return view('client.DeliveryInfo', compact('products'));



    // $quantity = $request->quantity ?? 1;

    // $product = ProductModel::findOrFail($productId);

    // return view('client.DeliveryInfo', compact('product', 'quantity'));
}



// public function storeSingle(Request $request)
// {
//     $validated = $request->validate([
//         'first_name' => 'required|string|max:100',
//         'last_name'  => 'required|string|max:100',
//         'address1'   => 'required|string|max:255',
//         'city'       => 'required|string|max:100',
//         'country'    => 'required|string|max:100',
//         'postcode'   => 'required|string|max:20',
//         'phone'      => 'required|string|max:20',
//         'email'      => 'required|email|max:255',

//         'ship_address1' => 'required_if:ship_different,1|nullable|string|max:255',
//         'ship_city'     => 'required_if:ship_different,1|nullable|string|max:100',
//         'ship_country'  => 'required_if:ship_different,1|nullable|string|max:100',
//         'ship_zip'      => 'required_if:ship_different,1|nullable|string|max:20',
//         'ship_phone'   => 'required_if:ship_different,1|nullable|string|max:20',
//     ]);

//     DB::beginTransaction();

//     try {
//         $product = ProductModel::lockForUpdate()
//             ->findOrFail($request->product_id);

//         if ($product->quantity <= 0) {
//             DB::rollBack();
//             return back()->with('error', 'Product is out of stock.');
//         }

//         // Price calculation
//         $originalPrice = $product->price;

//         if ($product->discount_type === 'rate') {
//             $discountValue = ($originalPrice * $product->discount_rate) / 100;
//         } elseif ($product->discount_type === 'amount') {
//             $discountValue = $product->discount_amount;
//         } else {
//             $discountValue = 0;
//         }

//         $discountValue = min($discountValue, $originalPrice);
//         $finalPrice = $originalPrice - $discountValue;


//         $lastOrder = OrderModel::lockForUpdate()
//         ->orderBy('id', 'desc')
//         ->first();

//         $nextNumber = $lastOrder
//         ? ((int) substr($lastOrder->bill_no, 2)) + 1
//         : 1;

//         $billNo = 'TN' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

//             Log::info('Storing orders db:', $request->session()->all());

//         // Create order
//         $order = orderModel::create([
//             'bill_no' => $billNo,
//             'name' => $validated['first_name'].' '.$validated['last_name'],
//             'email' => $validated['email'],
//             'mobile_number' => $validated['phone'],

//             'billing_address1' => $validated['address1'],
//             'billing_address2' => $request->address2,
//             'billing_city' => $validated['city'],
//             'billing_country' => $validated['country'],
//             'billing_postcode' => $validated['postcode'],

//             'shipping_address1' => $request->ship_different ? $validated['ship_address1'] : null,
//             'shipping_address2' => $request->ship_different ? $request->ship_address2 : null,
//             'shipping_city' => $request->ship_different ? $validated['ship_city'] : null,
//             'shipping_country' => $request->ship_different ? $validated['ship_country'] : null,
//             'shipping_postcode' => $request->ship_different ? $validated['ship_zip'] : null,

//             'status' => 'Pending',
//             'payment_status' => 'Pending',
//             'total' => $finalPrice,
//         ]);

//             Log::info('Storing orders item db:', $request->session()->all());

//         orderItemModel::create([
//             'order_id' => $order->id,
//             'product_id' => $product->id,
//             'image_path' => $product->image,
//             'quantity' => 1,
//             'price' => $finalPrice,
//             'total' => $finalPrice,
//         ]);

//         // Reduce stock
//         $product->quantity -= 1;
//         $product->save();

//         DB::commit();

//             Log::info('Order successfully:', $request->session()->all());

//         return redirect('/')
//             ->with('success', 'Order placed successfully!');

//     } catch (Exception $e) {
//         DB::rollBack();
//             Log::info('Something went wrong.' + $e, $request->session()->all());
//         return back()->with('error', 'Something went wrong.');
//     }
// }


public function storeOld(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required|string|max:100',
        'last_name'  => 'required|string|max:100',
        'address1'   => 'required|string|max:255',
        'city'       => 'required|string|max:100',
        'country'    => 'required|string|max:100',
        'postcode'   => 'required|string|max:20',
        'phone'      => 'required|string|max:20',
        'email'      => 'required|email|max:255',
    ]);   

    if($request->ship_different ?? 0)
    {
        $request->validate([       
        'ship_different'=> 'nullable|boolean',
        'ship_address1' => 'required_if:ship_different,1|string|max:255',
        'ship_address2' => 'nullable|string|max:255',
        'ship_city'     => 'required_if:ship_different,1|string|max:100',
        'ship_country'  => 'required_if:ship_different,1|string|max:100',
        'ship_zip'      => 'required_if:ship_different,1|string|max:20',
        'ship_phone'    => 'required_if:ship_different,1|string|max:20',
    ]);

    }

    $products = $request->products;

    if (!$products || count($products) == 0) {
        return back()->with('error', 'No products selected.');
    }


     session([
        'billing' => [
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'address1'   => $request->address1,
            'address2'   => $request->address2,
            'city'       => $request->city,
            'country'    => $request->country,
            'postcode'   => $request->postcode,
            'phone'      => $request->phone,
            'email'      => $request->email,
        ],
        'shipping' => $request->ship_different ? [
            'ship_address1' => $request->ship_address1,
            'ship_address2' => $request->ship_address2,
            'ship_city'     => $request->ship_city,
            'ship_country'  => $request->ship_country,
            'ship_zip'      => $request->ship_zip,
            'ship_phone'    => $request->ship_phone,
        ] : null,
        'ship_different' => $request->ship_different ?? 0,
    ]);

    DB::beginTransaction();

    try {

        $totalPrice = 0;
        $orderItems = [];

        foreach ($products as $data) {

            $product = ProductModel::lockForUpdate()->findOrFail($data['id']);
            $quantity = $data['quantity'];

            if ($product->quantity < $quantity) {
                DB::rollBack();
                return back()->with('error', "{$product->name} stock இல்லை.");
            }

            $originalPrice = $product->price;
            $discount = 0;

            if ($product->discount_type === 'rate') {
                $discount = ($originalPrice * $product->discount_rate) / 100;
            } elseif ($product->discount_type === 'amount') {
                $discount = $product->discount_amount;
            }

            $discount = min($discount, $originalPrice);
            $finalPrice = ($originalPrice - $discount) * $quantity;

            $totalPrice += $finalPrice;

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'price'      => $originalPrice,
                'total'      => $finalPrice,
                'image_path' => $product->image ?? null,
            ];

            $product->quantity -= $quantity;
            $product->save();
        }

        // Bill Number Generate
        $lastOrder = OrderModel::orderBy('id','desc')->first();
        $nextNumber = $lastOrder ? ((int) substr($lastOrder->bill_no, 2)) + 1 : 1;
        $billNo = 'TN' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        $order = OrderModel::create([
            'customer_id'      => session('client_id'),
            'bill_no' => $billNo,
            'name' => $validated['first_name'].' '.$validated['last_name'],
            'email' => $validated['email'],
            'mobile_number' => $validated['phone'],
            'billing_address1' => $validated['address1'],
            'billing_city' => $validated['city'],
            'billing_country' => $validated['country'],
            'billing_postcode' => $validated['postcode'],
            'status' => 'Pending',
            'payment_status' => 'Pending',
            'total' => $totalPrice,
        ]);

        foreach ($orderItems as $item) {
            OrderItemModel::create(array_merge($item, [
                'order_id' => $order->id
            ]));
        }

        DB::commit();

        return redirect()->route('order.status', ['order' => $order->id])
            ->with('success', 'Order placed successfully!');

    } catch (\Exception $e) {

        DB::rollBack();
        return back()->with('error', 'Something went wrong.');
    }
}

public function storeSingle(Request $request)
{
    $billing = session('billing');

    if (!$billing) {
        return back()->with('error', 'Please fill in your billing details first.');
    }

    $request->validate([
        'products'             => 'required|array|min:1',
        'products.*.id'        => 'required|integer',
        'products.*.quantity'  => 'required|integer|min:1',
    ]);

    $products = $request->products;

    if (!$products || count($products) == 0) {
        return back()->with('error', 'No products selected.');
    }

    $shipping = session('shipping');

    DB::beginTransaction();

    try {
        $totalPrice = 0;
        $orderItems = [];

        foreach ($products as $data) {
            $product  = ProductModel::lockForUpdate()->findOrFail($data['id']);
            $quantity = $data['quantity'];

            if ($product->quantity < $quantity) {
                DB::rollBack();
                return back()->with('error', "{$product->name} stock இல்லை.");
            }

            $originalPrice = $product->price;
            $discount = 0;

            if ($product->discount_type === 'rate') {
                $discount = ($originalPrice * $product->discount_rate) / 100;
            } elseif ($product->discount_type === 'amount') {
                $discount = $product->discount_amount;
            }

            $discount   = min($discount, $originalPrice);
            $finalPrice = ($originalPrice - $discount) * $quantity;
            $totalPrice += $finalPrice;

            $orderItems[] = [
                'product_id' => $product->id,
                'quantity'   => $quantity,
                'price'      => $originalPrice,
                'total'      => $finalPrice,
                'image_path' => $product->image ?? null,
            ];

            $product->quantity -= $quantity;
            $product->save();
        }

        $lastOrder  = OrderModel::orderBy('id', 'desc')->first();
        $nextNumber = $lastOrder ? ((int) substr($lastOrder->bill_no, 2)) + 1 : 1;
        $billNo     = 'TN' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        $order = OrderModel::create([
            'customer_id'      => session('client_id'),
            'bill_no'           => $billNo,
            'name'              => $billing['first_name'] . ' ' . $billing['last_name'],
            'email'             => $billing['email'],
            'mobile_number'     => $billing['phone'],
            'billing_address1'  => $billing['address1'],
            'billing_city'      => $billing['city'],
            'billing_country'   => $billing['country'],
            'billing_postcode'  => $billing['postcode'],
            'status'            => 'Pending',
            'payment_status'    => 'Pending',
            'total'             => $totalPrice,
        ]);

        foreach ($orderItems as $item) {
            OrderItemModel::create(array_merge($item, [
                'order_id' => $order->id
            ]));
        }

        DB::commit();

        session()->forget(['billing', 'shipping', 'ship_different']);

        return redirect()->route('order.status', ['order' => $order->id])
            ->with('success', 'Order placed successfully!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

public function orderStatus($order)
{
    $order = OrderModel::find($order);

    if (!$order) {
        return redirect()->route('home');
    }

    return view('Client.orderstatus', compact('order'));
}


public function saveBillingDetails(Request $request)
{
    Log::info('Store Payment Data: ' . json_encode($request->all()));

    $validated = $request->validate([
        'first_name'    => 'required|string|max:50',
        'last_name'     => 'required|string|max:50',
        'address1'      => 'required|string|max:255',
        'address2'      => 'nullable|string|max:255',
        'city'          => 'required|string|max:100',
        'country'       => 'required|string|max:100',
        'postcode'      => 'required|string|max:20',
        'phone'         => 'required|string|max:20',
        'email'         => 'required|email|max:100',
        'ship_different'=> 'nullable|boolean',
        'ship_address1' => 'required_if:ship_different,1|nullable|string|max:255',
        'ship_address2' => 'nullable|string|max:255',
        'ship_city'     => 'required_if:ship_different,1|nullable|string|max:100',
        'ship_country'  => 'required_if:ship_different,1|nullable|string|max:100',
        'ship_zip'      => 'required_if:ship_different,1|nullable|string|max:20',
        'ship_phone'    => 'required_if:ship_different,1|nullable|string|max:20',
    ]);

    session([
        'billing' => [
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'address1'   => $request->address1,
            'address2'   => $request->address2,
            'city'       => $request->city,
            'country'    => $request->country,
            'postcode'   => $request->postcode,
            'phone'      => $request->phone,
            'email'      => $request->email,
        ],
        'shipping' => $request->ship_different ? [
            'ship_address1' => $request->ship_address1,
            'ship_address2' => $request->ship_address2,
            'ship_city'     => $request->ship_city,
            'ship_country'  => $request->ship_country,
            'ship_zip'      => $request->ship_zip,
            'ship_phone'    => $request->ship_phone,
        ] : null,
        'ship_different' => $request->ship_different ?? 0,
    ]);

    session()->save();

    return redirect()->back()->with('success', 'Billing details saved successfully!');
}


public function account()
{
    if (!session()->has('client_id')) {
        return redirect()->route('ClientLogin');
    }

    $clientId = session('client_id');

    $customer = CustomerModel::findOrFail($clientId);

    $orders = orderModel::where('customer_id', $clientId)
        ->with('items.product')
        ->latest()
        ->get();

    return view('Client.account', compact('customer', 'orders'));
}

}
