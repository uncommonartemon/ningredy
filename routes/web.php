<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MainController;
use App\Http\Controllers\SessionController;

Route::get('/', [MainController::class, 'index']);
Route::get('/category/{category}', [MainController::class, 'productsByCategory'])->name('productsByCategory');
Route::get('/search-result', [MainController::class, 'productsBySearch'])->name('productsBySearch');
Route::get('/product/{id}', [MainController::class, 'productById'])->name('productById');

Route::prefix('/cart')->group(function() {
    Route::post('/add/{id}', [SessionController::class, 'addToCart'])->name('cart.add');
    Route::delete('/delete/{id}', [SessionController::class, 'removeFromCart'])->name('cart.remove');
    Route::delete('/clear', [SessionController::class, 'clearCart'])->name('cart.clear');
    Route::patch('/addQty/{id}', [SessionController::class, 'addQty'])->name('cart.addQty');
    Route::patch('/removeQty/{id}', [SessionController::class, 'removeQty'])->name('cart.removeQty');
});