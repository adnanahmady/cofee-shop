<?php

use Illuminate\Support\Facades\Route;

Route::group([], base_path('routes/api/v1/auth.php'));

Route::middleware('auth:api')->group(function (): void {
    Route::name('products.')->group(base_path('routes/api/v1/products.php'));
    Route::name('orders.')->group(base_path('routes/api/v1/orders.php'));
    Route::name('order-items.')->group(
        base_path('routes/api/v1/order-items.php')
    );
    Route::name('order-statuses.')->group(
        base_path('routes/api/v1/order-statuses.php')
    );
});
