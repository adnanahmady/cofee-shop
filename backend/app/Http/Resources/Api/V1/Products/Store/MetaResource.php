<?php

namespace App\Http\Resources\Api\V1\Products\Store;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MetaResource extends JsonResource
{
    public const CURRENCY = 'currency';
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
            self::CURRENCY => new CurrencyResource(
                $this->resource->getPriceObject()->getCurrency()
            ),
        ];
    }
}
