<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MainCategoryModel;
use App\Models\ProductModel;
use App\Models\BrandModel;
use App\Models\CustomerModel;
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
    // Log the session data before flushing (clear)
    Log::info('Session Data Before Logout:', $request->session()->all());

    // Clear all session data
    $request->session()->flush();

    // Log the session data after flushing
    Log::info('Session Data After Logout:', $request->session()->all());

    // Optionally regenerate session ID for security
    $request->session()->regenerate();

    // Redirect to the ClientLogin route
    return redirect()->route('ClientLogin');
}

// public function logout(Request $request)
// { 
//        dd($request->session()->all());
//         $request->session()->flush();

//     return redirect()->route('ClientLogin'); 
// }
     

}
