<?php

namespace App\Http\Resources\Api\V1\Shared;

use App\Models\Customization;
use App\Models\Option;
use App\Repositories\CustomizationRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomizationResource extends JsonResource
{
    public const NAME = 'name';
    public const OPTIONS = 'options';

    /** @var Customization */
    public $resource;
    private readonly CustomizationRepository $customizationRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->customizationRepository = new CustomizationRepository();
    }

    public function toArray(Request $request): array
    {
        return [
            self::NAME => $this->resource->getName(),
            self::OPTIONS => $this->customizationRepository
                ->getOptions($this->resource)
                ->map(fn (Option $o) => $o->getName()),
        ];
    }
}
