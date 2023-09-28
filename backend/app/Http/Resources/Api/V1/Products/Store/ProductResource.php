<?php

namespace App\Http\Resources\Api\V1\Products\Store;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public const ID = 'id';
    public const NAME = 'name';
    public const AMOUNT = 'amount';
    public const PRICE = 'price';

    /**
     * @var Product
     */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            self::ID => $this->resource->getId(),
            self::NAME => $this->resource->getName(),
            self::AMOUNT => $this->resource->getAmount(),
            self::PRICE => $this->resource->getPriceObject()->represent(),
        ];
    }
}
