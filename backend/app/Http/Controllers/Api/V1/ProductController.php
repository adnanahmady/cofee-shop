<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Products\StoreRequest;
use App\Http\Resources\Api\V1\Products\Store;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    public function store(
        StoreRequest $request,
        ProductRepository $productRepository
    ): Store\StoredResource {
        $product = $productRepository->create(
            $request->getName(),
            $request->getAmount(),
            $request->getPriceObject()
        );

        return new Store\StoredResource($product);
    }
}
