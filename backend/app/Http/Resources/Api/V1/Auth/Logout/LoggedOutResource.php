<?php

namespace App\Http\Resources\Api\V1\Auth\Logout;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoggedOutResource extends JsonResource
{
    public const DATA = 'data';
    public const META = 'meta';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            self::DATA => __('You\'r logged out from system'),
            self::META => new UserResource($this->resource),
        ];
    }
}
