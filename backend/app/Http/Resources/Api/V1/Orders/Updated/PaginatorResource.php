<?php

namespace App\Http\Resources\Api\V1\Orders\Updated;

use App\Http\Resources\Api\V1\Orders\Shared\DataResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class PaginatorResource extends JsonResource
{
    public const DATA = 'data';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            self::DATA => new DataResource($this->resource),
        ];
    }

    public function toResponse($request): JsonResponse
    {
        return parent::toResponse($request)
            ->setStatusCode(Response::HTTP_OK);
    }
}
