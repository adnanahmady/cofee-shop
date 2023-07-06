<?php

namespace App\Repositories;

use App\Models\OrderStatus;
use App\Support\Values\ValueInterface;

class OrderStatusRepository
{
    public function firstOrCreate(ValueInterface $status): OrderStatus
    {
        return OrderStatus::firstOrCreate([
            OrderStatus::NAME => $status,
        ]);
    }
}
