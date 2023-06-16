<?php

namespace App\Http\Resources\Api\V1\Auth\Register;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public const ID = 'id';
    public const NAME = 'name';
    public const EMAIL = 'email';

    /** @var User */
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
            self::NAME => $this->resource->getName()->getFullName(),
            self::EMAIL => $this->resource->getEmail(),
        ];
    }
}
