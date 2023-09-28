<?php

namespace App\Http\Resources\Api\V1\Auth\Login;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MetaResource extends JsonResource
{
    public const USER = 'user';

    /**
     * @var User
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
            self::USER => new UserResource($this->resource),
        ];
    }
}
