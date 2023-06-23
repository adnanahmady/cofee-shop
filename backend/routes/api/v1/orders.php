<?php

use App\Http\Controllers\Api\V1\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/orders', [OrderController::class, 'index'])->name('index');
Route::post('/orders', [OrderController::class, 'store'])->name('store');
