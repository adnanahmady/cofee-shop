<?php

namespace App\Http\Controllers\Api\V1\Orders;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Orders\Updated;
use App\Models\Order;
use App\Services\OrderTypes\UpdateService;

class TypeController extends Controller
{
    public function update(
        Order $order,
        UpdateService $typeService,
    ): Updated\PaginatorResource {
        return new Updated\PaginatorResource(
            $typeService->update($order)
        );
    }
}
