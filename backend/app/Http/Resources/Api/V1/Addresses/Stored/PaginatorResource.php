<?php

namespace App\Http\Resources\Api\V1\Addresses\Stored;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginatorResource extends JsonResource
{
    public const DATA = 'data';
    public const META = 'meta';

    /**
     * @var Address
     */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            self::DATA => new DataResource($this->resource),
            self::META => new MetaResource($this->resource),
        ];
    }
}
