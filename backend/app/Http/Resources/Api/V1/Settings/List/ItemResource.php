<?php

namespace App\Http\Resources\Api\V1\Settings\List;

use App\Interfaces\SettingInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public const NAME = 'name';
    public const VALUE = 'value';
    public const DEFAULT = 'default';

    /**
     * @var SettingInterface
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
            self::NAME => $this->resource->name(),
            self::VALUE => $this->resource->value(),
            self::DEFAULT => $this->resource->default(),
        ];
    }
}
