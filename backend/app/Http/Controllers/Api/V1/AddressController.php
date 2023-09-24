<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Address\StoreRequest;
use App\Http\Resources\Api\V1\Addresses\Stored\PaginatorResource;
use App\Repositories\AddressRepository;

class AddressController
{
    public function store(
        StoreRequest $request,
        AddressRepository $addressRepository,
    ): PaginatorResource {
        return new PaginatorResource($addressRepository->create($request));
    }
}
