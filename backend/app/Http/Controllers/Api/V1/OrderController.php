<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Orders\GetListRequest;
use App\Http\Requests\Api\V1\Orders\StoreRequest;
use App\Repositories\OrderRepository;
use App\Services\OrderService;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\Orders\List;
use App\Http\Resources\Api\V1\Orders\Store;

class OrderController extends Controller
{
    public function index(
        GetListRequest $request,
        OrderService $orderService,
    ): List\PaginatorCollection {
        return new List\PaginatorCollection(
            $orderService->getPaginated(
                $request->user(),
                $request->getPage(),
                $request->getPerPage(),
            )
        );
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
