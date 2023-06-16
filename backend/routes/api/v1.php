<?php

use Illuminate\Support\Facades\Route;

Route::group([], base_path('routes/api/v1/auth.php'));

Route::middleware('auth:api')->group(function (): void {
    Route::name('products.')->group(base_path('routes/api/v1/products.php'));
});