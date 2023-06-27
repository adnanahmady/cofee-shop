<?php

namespace App\Http\Resources\Api\V1\Products\List;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatorCollection extends ResourceCollection
{
    public const DATA = 'data';

    public function toArray(Request $request): array
    {
        return [
            self::DATA => $this->collection->map(
                fn ($resource) => new ItemResource($resource)
            ),
        ];
    }
}
