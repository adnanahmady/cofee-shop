<?php

namespace App\Http\Resources\Api\V1\Orders\List;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Collection;

class DataCollection extends ResourceCollection
{
    public function toArray(Request $request): Collection
    {
        return $this->collection->map(
            fn ($resource) => new OrderResource($resource)
        );
    }
}
