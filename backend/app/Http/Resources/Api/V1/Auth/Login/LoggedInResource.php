<?php

namespace App\Http\Resources\Api\V1\Auth\Login;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoggedInResource extends JsonResource
{
    public const DATA = 'data';
    public const META = 'meta';

    public function __construct($resource, private User $user)
    {
        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            self::DATA => new DataResource($this->resource),
            self::META => new MetaResource($this->user),
        ];
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
