<?php

namespace App\Http\Resources\Api\V1\Settings\List;

use App\Interfaces\SettingInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataResource extends JsonResource
{
    /**
     * @var array<SettingInterface>
     */
    public $resource;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_map(
            fn(SettingInterface $s) => new ItemResource($s),
            $this->resource
        );
    }
}
