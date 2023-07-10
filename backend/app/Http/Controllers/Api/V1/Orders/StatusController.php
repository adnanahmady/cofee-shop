<?php

namespace App\Http\Controllers\Api\V1\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\OrderStatuses\UpdateRequest;
use App\Http\Resources\Api\V1\Orders\Updated\PaginatorResource;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Repositories\OrderStatusRepository;
use App\Support\OrderStateDeterminer\Determiner;

class StatusController extends Controller
{
    public function update(
        UpdateRequest $request,
        Order $order,
        OrderRepository $repository,
        OrderStatusRepository $statusRepository,
    ): PaginatorResource {
        $determiner = new Determiner(
            choiceHolder: $request,
            currentState: $repository->getStatus($order)?->getName()
        );
        $state = $determiner->determine();
        $status = $statusRepository->firstOrCreate($state);
        $repository->changeStatus($order, $status);

        return new PaginatorResource($order);
    }
}
