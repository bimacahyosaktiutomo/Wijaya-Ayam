<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Home', [
//         // 'canLogin' => Route::has('login'),
//         // 'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// })->name('home');
Route::get('/', [ProductController::class, 'index'])->name('home');

// JANGAN LUPA, NARO DI ROUTE PAKENYA {id} bukan {$id} 
// JANGAN LUPA, NARO DI ROUTE PAKENYA {id} bukan {$id} 
// JANGAN LUPA, NARO DI ROUTE PAKENYA {id} bukan {$id} 

//Dashboard
Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
    // Produk
    Route::get('/dashboard/product', [ProductController::class, 'dashboard'] )->name('dashboard.product');
    Route::get('/dashboard/product/add', function () {return Inertia::render('Admin/ProductForm');})->name('dashboard.product.add');
    Route::post('/dashboard/product/store/', [ProductController::class, 'store'] )->name('dashboard.product.store');
    Route::get('/dashboard/product/edit/{id}', [ProductController::class, 'edit'] )->name('dashboard.product.edit');
    Route::patch('/dashboard/product/update/{id}', [ProductController::class, 'update'] )->name('dashboard.product.update');
    Route::delete('/dashboard/product/delete/{id}', [ProductController::class, 'destroy'] )->name('dashboard.product.delete');

    //User
    Route::get('/dashboard/user/', [UserController::class, 'dashboard'])->name('dashboard.user');
    Route::get('/dashboard/user/edit/{id}', [UserController::class, 'edit'])->name('dashboard.user.edit');
    Route::patch('/dashboard/user/update/{id}', [UserController::class, 'update'])->name('dashboard.user.update');
    Route::delete('/dashboard/user/delete/{id}', [UserController::class, 'destroy'])->name('dashboard.user.delete');
    Route::patch('/dashboard/user/role/{id}', [UserController::class, 'updateRole'])->name('dashboard.user.role');

    //Order
    Route::get('/dashboard/order/', [OrderController::class, 'dashboard'])->name('dashboard.order');
    Route::get('/dashboard/order/{id}/details/', [OrderController::class, 'fromDashboardDetail'])->name('dashboard.order.details');
    Route::patch('/dashboard/order/status/{id}', [OrderController::class, 'updateStatus'])->name('dashboard.order.status');

    Route::get('/dashboard/report', [ReportController::class, 'index'])->name('dashboard.report');
});
//langsung semua
// Route::resource('dashboard', ProductController::class);

// Cart
Route::middleware(['auth', 'verified'])->group(function () {
    // Cart
    Route::get('/getCartInfo', [CartController::class, 'getCartInfo'])->name('cartInfo');
    Route::get('/cart', [CartController::class, 'cart'])->name('cart');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/updateQty/{id}', [CartController::class, 'updateQuantity'])->name('cart.updateqty');
    Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Transactions
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/order', [OrderController::class, 'store'])->name('order');
    Route::get('/order/history', [OrderController::class, 'history'])->name('history');
    Route::get('/order/{id}/details', [OrderController::class, 'detail'])->name('details');
    Route::patch('/order/status/{id}', [OrderController::class, 'updateStatus'])->name('update.status');
});

// Pages
Route::get('/about', function () {
    return Inertia::render('About');
})->name('about');
Route::get('/product', [ProductController::class, 'product'])->name('product');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
