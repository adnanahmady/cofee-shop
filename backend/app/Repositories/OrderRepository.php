<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Collection;

class OrderRepository
{
    public function getItems(Order $order): Collection
    {
        return $order->items;
    }
}
