<?php

use App\Http\Controllers\Api\V1\DeliveryTypeController;
use Illuminate\Support\Facades\Route;

Route::get('/delivery-types', [DeliveryTypeController::class, 'index'])
    ->name('index');
