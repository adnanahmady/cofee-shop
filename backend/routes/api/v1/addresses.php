<?php

use App\Http\Controllers\Api\V1\AddressController;
use Illuminate\Support\Facades\Route;

Route::post(
    uri: 'addresses',
    action: [AddressController::class, 'store']
)->name('store');
