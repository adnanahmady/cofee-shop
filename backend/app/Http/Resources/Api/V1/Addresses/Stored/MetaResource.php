<?php

namespace App\Http\Resources\Api\V1\Addresses\Stored;

use App\Http\Resources\Api\V1\Auth\Login\UserResource;
use App\Models\User;
use App\Repositories\AddressRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MetaResource extends JsonResource
{
    public const USER = 'user';

    /** @var User */
    public $resource;

    public function __construct($resource)
    {
        $repository = new AddressRepository();

        parent::__construct($repository->getUser($resource));
    }

    public function toArray(Request $request): array
    {
        return [
            self::USER => new UserResource($this->resource),
        ];
    }
}
