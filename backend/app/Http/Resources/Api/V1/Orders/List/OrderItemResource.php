<?php

namespace App\Http\Resources\Api\V1\Orders\List;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    public const ID = 'id';
    public const NAME = 'product_name';
    public const AMOUNT = 'amount';
    public const PRICE = 'price';

    /** @var OrderItem */
    public $resource;

    public function __construct($resource)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            self::ID => $this->resource->getId(),
            self::NAME => $this->resource->product->getName(),
            self::AMOUNT => $this->resource->getAmount(),
            self::PRICE => $this->resource->getPriceObject()->represent(),
        ];
    }
}
