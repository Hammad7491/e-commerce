<?php

use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\ProfileController;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Frontend Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $categories = Category::with([
        'products' => fn ($q) => $q->with('firstMedia')->latest()
    ])->orderBy('name')->get();

    $products = Product::with(['category', 'firstMedia'])
        ->latest()
        ->get();

    return view('users.home', compact('categories', 'products'));
})->name('home');

Route::get('/category/{category}', function (Category $category) {
    $categories = Category::orderBy('name')->get();

    $products = Product::with(['category', 'firstMedia'])
        ->where('category_id', $category->id)
        ->latest()
        ->get();

    return view('users.category', compact('categories', 'category', 'products'));
})->name('users.category');

Route::get('/product/{product}', function (Product $product) {
    $categories = Category::orderBy('name')->get();

    $product->load(['category', 'media', 'firstMedia']);

    return view('users.product-show', compact('categories', 'product'));
})->name('users.product');

Route::get('/orders', function () {
    $categories = Category::orderBy('name')->get();

    return view('users.orders', compact('categories'));
})->name('users.orders');

Route::get('/wishlist', function () {
    $categories = Category::orderBy('name')->get();

    return view('users.wishlist', compact('categories'));
})->name('users.wishlist');

Route::get('/contact-us', function () {
    $categories = Category::orderBy('name')->get();

    return view('users.contact', compact('categories'));
})->name('users.contact');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('admin-users', AdminUserController::class)->except(['show']);
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::post('products/bulk-destroy', [ProductController::class, 'bulkDestroy'])->name('products.bulkDestroy');
        Route::resource('products', ProductController::class)->only([
            'index',
            'create',
            'store',
            'show',
            'edit',
            'update',
            'destroy'
        ]);
        Route::resource('notifications', AdminNotificationController::class)->only([
            'index',
            'create',
            'store'
        ]);
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';