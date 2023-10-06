<?php

use Illuminate\Support\Facades\Route;

Route::group([], base_path('routes/api/v1/auth.php'));

Route::middleware('auth:api')->group(function (): void {
    Route::name('addresses.')->group(base_path('routes/api/v1/addresses.php'));
    Route::name('products.')->group(base_path('routes/api/v1/products.php'));
    Route::name('orders.')->group(base_path('routes/api/v1/orders.php'));
    Route::name('order-items.')->group(
        base_path('routes/api/v1/order-items.php')
    );
    Route::name('order-statuses.')->group(
        base_path('routes/api/v1/order-statuses.php')
    );
    Route::name('order-types.')->group(
        base_path('routes/api/v1/order-types.php')
    );
    Route::name('delivery-types.')->group(
        base_path('routes/api/v1/delivery-types.php')
    );
    Route::name('settings.')->group(base_path('routes/api/v1/settings.php'));
    Route::name('currencies.')->group(base_path('routes/api/v1/currencies.php'));
});
