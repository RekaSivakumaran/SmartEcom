<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ClientController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('Admin.Dashboard');
})->name('dashboard');
// Route::get('/', function () {
//     return view('Layout.app');
// });

// Route::get('/', function () {
//     return view('Layout.app');
// });

Route::get('/client', function () {
    return view('Layout.Client');
});

Route::get('/users', function () {
    return view('Admin.user');
})->name('users');

Route::get('/customers', function () {
    return view('Admin.customer');
})->name('customers');

Route::get('/category', function () {
    return view('Admin.category');
})->name('categorys');

Route::get('/brands', function () {
    return view('Admin.brand');
})->name('brands');

Route::get('/products', function () {
    return view('Admin.product');
})->name('products');


Route::get('/users', [UserController::class, 'index'])->name('users.index');;
Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
Route::get('/users', [UserController::class, 'index'])->name('users.index');

Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::post('/customers/update-status/{id}', [CustomerController::class, 'updateStatus'])->name('customers.updateStatus');

// Route::get('/main-category', [MainCategoryController::class, 'index'])->name('maincategory.index');
Route::get('/category', [MainCategoryController::class, 'index'])->name('categorys.index');
Route::post('/main-category/store', [MainCategoryController::class, 'store'])->name('maincategory.store');
Route::post('/main-category/update/{id}', [MainCategoryController::class, 'update'])->name('maincategory.update');
Route::delete('/main-category/delete/{id}', [MainCategoryController::class, 'destroy'])->name('maincategory.destroy');

Route::get('/brands', [BrandController::class, 'index'])->name('brand.index');
Route::post('/brands', [BrandController::class, 'store'])->name('brands.store');
Route::post('/brand/update/{id}', [BrandController::class, 'update'])->name('brand.update');
Route::delete('/brand/destroy/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');


Route::get('/subcategories', [SubCategoryController::class, 'index'])->name('subcategories.index');
Route::post('/subcategories/store', [SubCategoryController::class, 'store'])->name('subcategories.store');
Route::post('/subcategories/update/{id}', [SubCategoryController::class, 'update'])->name('sub-category.update');
Route::post('/subcategories/delete/{id}', [SubCategoryController::class, 'destroy'])->name('sub-category.destroy');

Route::get('/Products', [ProductController::class, 'index'])->name('products.index');
Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');

Route::get('/Login', [LoginController::class, 'showLoginForm'])->name('Admin.login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.submit');
Route::get('/admin/dashboard', [LoginController::class, 'dashboard']);
Route::get('/admin/logout', [LoginController::class, 'logout']);




// Route::get('/dashboard', function () {
//     return view('Admin.Dashboard');
// })->name('dashboard');


Route::get('/client', function () {
    return view('Client.home');
})->name('home');

Route::get('/client/about', function () {
    return view('Client.about');   
})->name('about');

Route::get('/client/contactus', function () {
    return view('Client.contactus');   
})->name('contactus');

Route::get('/client/Item', function () {
    return view('Client.Item');   
})->name('Item');

Route::get('/client', [ClientController::class, 'index'])->name('home');
Route::get('/product/{id}', [ClientController::class, 'show'])->name('Client.shopdetails');
Route::get('/client/Item', [ClientController::class, 'showCategories'])
    ->name('Item');

Route::get('/products/subcategory/{id}', 
    [ClientController::class, 'productsBySubCategory']
)->name('products.bySubCategory');
