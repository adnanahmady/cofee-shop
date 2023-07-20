<?php

namespace App\Http\Resources\Api\V1\Orders\List;

use App\Http\Resources\Api\V1\Orders\Shared\DataResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderResource extends DataResource
{
    public const ORDERED_AT = 'ordered_at';
    public const UPDATED_AT = 'updated_at';

    /** @var Order */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request) + [
            self::ORDERED_AT => $this->resource->getCreatedAt(),
            self::UPDATED_AT => $this->resource->getUpdatedAt(),
        ];
    }
}
