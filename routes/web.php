<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Katalog: semua user login boleh lihat (Policy viewAny/view = true)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    // Edit/update: dicek di controller via authorize('update') — admin+editor lolos
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    // Delete: authorize('delete') — hanya admin
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Order: user lihat miliknya, admin/editor lihat semua (OrderPolicy)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

// Tugas P11 No.6 + No.8: grup admin dilindungi custom middleware 'admin'
// (bootstrap/app.php alias EnsureAdmin). Hanya role=admin.
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Contoh multi-role fleksibel: hanya admin & editor (user biasa -> 403)
Route::middleware(['auth', 'role:admin,editor'])->prefix('editor')->name('editor.')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
});

require __DIR__.'/auth.php';
