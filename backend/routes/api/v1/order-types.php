<?php

use App\Enums\AbilityEnum;
use App\Http\Controllers\Api\V1\Orders\TypeController;
use Illuminate\Support\Facades\Route;

Route::patch('/orders/{order}/types', [TypeController::class, 'update'])
    ->name('update')
//    ->where('order', '[1-9]\d*')
//    ->middleware(sprintf(
//        'ability:%s',
//        join(',', [AbilityEnum::SetOrderStatus->slugify()])
//    ))
;
