<?php

use App\Enums\AbilityEnum;
use App\Http\Controllers\Api\V1\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('/products', [ProductController::class, 'store'])
    ->name('store')
    ->middleware(sprintf(
        'ability:%s',
        join(',', [AbilityEnum::AddProduct->slugify()])
    ));
