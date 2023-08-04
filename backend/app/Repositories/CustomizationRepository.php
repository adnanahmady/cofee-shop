<?php

namespace App\Repositories;

use App\Models\Customization;
use Illuminate\Database\Eloquent\Collection;

class CustomizationRepository
{
    public function getOptions(Customization $customization): Collection
    {
        return $customization->options;
    }
}
