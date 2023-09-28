<?php

namespace App\Http\Resources\Api\V1\Shared;

use App\Models\Option;
use App\Repositories\OptionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomizationResource extends JsonResource
{
    public const CUSTOMIZATION_ID = 'customization_id';
    public const CUSTOMIZATION_NAME = 'customization_name';
    public const SELECTED_OPTION_ID = 'selected_option_id';
    public const SELECTED_OPTION_NAME = 'selected_option_name';

    /**
     * @var Option
     */
    public $resource;
    private readonly OptionRepository $optionRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->optionRepository = new OptionRepository();
    }

    public function toArray(Request $request): array
    {
        $customization = $this->optionRepository
            ->getCustomization($this->resource);

        return [
            self::CUSTOMIZATION_ID => $customization->getId(),
            self::CUSTOMIZATION_NAME => $customization->getName(),
            self::SELECTED_OPTION_ID => $this->getId(),
            self::SELECTED_OPTION_NAME => $this->getName(),
        ];
    }
}
