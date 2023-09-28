<?php

namespace App\Http\Resources\Api\V1\Products\Store;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    public const ID = 'id';
    public const NAME = 'name';
    public const CODE = 'code';

    /**
     * @var Currency
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
            self::CODE => $this->resource->getCode(),
        ];
    }
}
