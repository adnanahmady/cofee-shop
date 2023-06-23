<?php

use App\Http\Controllers\Api\V1\OrderItemController;
use Illuminate\Support\Facades\Route;

Route::patch(
    '/orders/items/{orderItem}',
    [OrderItemController::class, 'update']
)->name('update')
->where('orderItem', '[1-9]\d*');
