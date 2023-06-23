<?php

namespace App\Http\Resources\Api\V1\OrderItems;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataResource extends JsonResource
{
    public const AMOUNT = 'amount';

    /** @var OrderItem */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            self::AMOUNT => $this->resource->getAmount(),
        ];
    }
}
