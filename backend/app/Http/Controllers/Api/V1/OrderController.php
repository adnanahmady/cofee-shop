<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Orders\StoreRequest;
use App\Repositories\OrderRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Orders\List;
use App\Http\Resources\Api\V1\Orders\Store;

class OrderController extends Controller
{
    public function index(Request $request): List\PaginatorResource
    {
        return new List\PaginatorResource($request->user()->orders);
    }

    public function store(
        StoreRequest $request,
        OrderRepository $orderRepository
    ): Store\PaginatorResource {
        $order = $orderRepository->orderProducts(
            $request->user(),
            $request->getProducts()
        );

        return new Store\PaginatorResource($order);
    }
}
