<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;


 
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



// Route::get('/dashboard', function () {
//     return view('Admin.Dashboard');
// })->name('dashboard');