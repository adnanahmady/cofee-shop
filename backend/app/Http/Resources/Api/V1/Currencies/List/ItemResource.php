<?php

namespace App\Http\Resources\Api\V1\Currencies\List;

use App\DataTransferObjects\Api\RateDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public const CODE = 'code';
    public const RATE = 'rate';

    /**
     * @var RateDto
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
            self::CODE => $this->resource->code(),
            self::RATE => $this->resource->rate(),
        ];
    }
}
