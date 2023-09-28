<?php

namespace App\Http\Resources\Api\V1\Addresses\Stored;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataResource extends JsonResource
{
    public const TITLE = 'title';
    public const CITY = 'city';
    public const STREET = 'street';
    public const PLATE_NUMBER = 'plate_number';
    public const POSTAL_CODE = 'postal_code';
    public const DESCRIPTION = 'description';

    /**
     * @var Address
     */
    public $resource;

    public function toArray(Request $request): array
    {
        return [
            self::TITLE => $this->resource->getTitle(),
            self::CITY => $this->resource->getCity(),
            self::STREET => $this->resource->getStreet(),
            self::PLATE_NUMBER => $this->resource->getPlateNumber(),
            self::POSTAL_CODE => $this->resource->getPostalCode(),
            self::DESCRIPTION => $this->resource->getDescription(),
        ];
    }
}
