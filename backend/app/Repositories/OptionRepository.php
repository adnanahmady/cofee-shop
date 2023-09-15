<?php

namespace App\Repositories;

use App\Models\Customization;
use App\Models\Option;

class OptionRepository
{
    public function find(int $optionId): null|Option
    {
        return Option::find($optionId);
    }

    public function getCustomization(Option $item): Customization
    {
        return $item->customization;
    }
}
