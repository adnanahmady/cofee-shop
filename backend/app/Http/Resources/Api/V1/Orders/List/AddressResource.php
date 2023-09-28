<?php

namespace App\Http\Resources\Api\V1\Orders\List;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
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
            self::CITY => $this->resource->getCity(),
            self::STREET => $this->resource->getStreet(),
            self::PLATE_NUMBER => $this->resource->getPlateNumber(),
            self::POSTAL_CODE => $this->resource->getPostalCode(),
            self::DESCRIPTION => $this->resource->getDescription(),
        ];
    }
}
