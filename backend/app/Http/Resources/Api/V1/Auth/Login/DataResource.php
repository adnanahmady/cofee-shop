<?php

namespace App\Http\Resources\Api\V1\Auth\Login;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataResource extends JsonResource
{
    public const ACCESS_TOKEN = 'access_token';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            self::ACCESS_TOKEN => $this->resource,
        ];
    }
}
