<?php

namespace App\Http\Resources\Api\V1\Currencies\List;

use App\DataTransferObjects\Api\RateDto;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginatorResource extends JsonResource
{
    public const DATA = 'data';

    /**
     * @var array<RateDto>
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
            self::DATA => array_map(
                fn(RateDto $r) => new ItemResource($r),
                $this->resource
            ),
        ];
    }
}
