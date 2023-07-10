<?php

use App\Enums\AbilityEnum;
use App\Http\Controllers\Api\V1\Orders\StatusController;
use Illuminate\Support\Facades\Route;

Route::patch('/orders/{order}/statuses', [StatusController::class, 'update'])
    ->name('update')
    ->where('order', '[1-9]\d*')
    ->middleware(sprintf(
        'ability:%s',
        join(',', [AbilityEnum::SetOrderStatus->slugify()])
    ));
