<?php

namespace App\Http\Resources\Api\V1\OrderTypes\List;

use App\Http\Resources\Api\V1\Shared\DeliveryTypeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DataCollection extends ResourceCollection
{
    public const DATA = 'data';

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            self::DATA => $this->collection->map(
                fn ($r) => new DeliveryTypeResource($r)
            ),
        ];
    }
}
