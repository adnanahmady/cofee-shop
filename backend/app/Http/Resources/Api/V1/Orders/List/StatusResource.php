<?php

namespace App\Http\Resources\Api\V1\Orders\List;

use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatusResource extends JsonResource
{
    public const ID = 'id';
    public const NAME = 'name';

    /** @var OrderStatus */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            self::ID => $this->resource->getId(),
            self::NAME => $this->resource->getName(),
        ];
    }
}
