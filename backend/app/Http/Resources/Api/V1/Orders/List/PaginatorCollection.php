<?php

namespace App\Http\Resources\Api\V1\Orders\List;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PaginatorCollection extends ResourceCollection
{
    public const DATA = 'data';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            self::DATA => new DataCollection($this->resource),
        ];
    }
}
