<?php

namespace App\Http\Controllers\Api\V1\Orders;

use App\Exceptions\Models\InvalidOrderItemAmountException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\OrderItems\UpdateRequest;
use App\Http\Resources\Api\V1\OrderItems;
use App\Models\OrderItem;
use App\Repositories\OrderItemRepository;
use Illuminate\Http\Response;

class OrderItemController extends Controller
{
    /**
     * @throws InvalidOrderItemAmountException
     */
    public function update(
        UpdateRequest $request,
        OrderItem $orderItem,
        OrderItemRepository $orderItemRepository
    ): OrderItems\PaginatorResource|Response {
        if ($request->getAmount() < 1) {
            $orderItemRepository->delete($orderItem);

            return new Response(status: Response::HTTP_NO_CONTENT);
        }
        $orderItem = $orderItemRepository->updateAmount(
            $orderItem,
            $request->getAmount()
        );

        return new OrderItems\PaginatorResource($orderItem);
    }
}
