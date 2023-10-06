<?php

use App\Http\Controllers\Api\V1\CurrencyController;
use Illuminate\Support\Facades\Route;

Route::get(
    uri: 'currencies',
    action: [CurrencyController::class, 'index']
)->name('index');
