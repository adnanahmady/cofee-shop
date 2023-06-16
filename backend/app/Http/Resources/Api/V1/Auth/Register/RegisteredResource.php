<?php

namespace App\Http\Resources\Api\V1\Auth\Register;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisteredResource extends JsonResource
{
    public const DATA = 'data';

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
            self::DATA => new UserResource($this->resource),
        ];
    }

    public function toResponse($request): JsonResponse
    {
        return parent::toResponse($request)
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
