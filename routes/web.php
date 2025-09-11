<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductController;
use App\Http\Controllers\PaymentController;

Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/checkout/{id}', [ProductController::class, 'checkout'])->name('checkout');

Route::post('/payment/store', [PaymentController::class, 'store'])->name('payment.store');
