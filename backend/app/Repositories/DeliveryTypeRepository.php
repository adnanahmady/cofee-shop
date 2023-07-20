<?php

namespace App\Repositories;

use App\Models\DeliveryType;

class DeliveryTypeRepository
{
    public function findById(int $id): DeliveryType|null
    {
        return DeliveryType::find($id);
    }
}
