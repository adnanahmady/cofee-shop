<?php

namespace App\Http\Resources\Api\V1\Shared;

use App\Models\DeliveryType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryTypeResource extends JsonResource
{
    public const ID = 'id';
    public const NAME = 'name';

    /**
     * @var DeliveryType
     */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            self::ID => $this->resource->getId(),
            self::NAME => $this->resource->getName(),
        ];
    }
}
