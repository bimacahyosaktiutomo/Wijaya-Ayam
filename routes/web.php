<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Models\Product;
use App\Models\User;
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

//Dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [ProductController::class, 'dashboard'] )->name('dashboard');

    // Produk
    Route::get('/dashboard/product/add', function () {return Inertia::render('Admin/ProductForm');})->name('dashboard.add');
    Route::post('/dashboard/product/store/', [ProductController::class, 'store'] )->name('dashboard.store');
    Route::get('/dashboard/product/edit/{id}', function ($id) {
        $product = Product::findOrFail($id); 
        return Inertia::render('Admin/ProductForm', [
            'product' => $product, 
            'isEdit' => true,
        ]);
    })->name('dashboard.edit');
    Route::patch('/dashboard/product/update/{id}', [ProductController::class, 'update'] )->name('dashboard.update');
    Route::delete('/dashboard/product/delete/{id}', [ProductController::class, 'destroy'] )->name('dashboard.delete');

    //User
    route::get('/dashboard/user/edit', [User::class, 'edit']);
});
//langsung semua
// Route::resource('dashboard', ProductController::class);

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
