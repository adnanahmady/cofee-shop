<?php

namespace App\Repositories;

use App\Models\DeliveryType;
use Illuminate\Database\Eloquent\Collection;

class DeliveryTypeRepository
{
    public function findById(int $id): DeliveryType|null
    {
        return DeliveryType::find($id);
    }

    public function getAvailable(): Collection
    {
        return DeliveryType::all();
    }
}
