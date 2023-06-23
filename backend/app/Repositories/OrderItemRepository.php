<?php

namespace App\Repositories;

use App\Models\OrderItem;
use App\Models\Product;

class OrderItemRepository
{
    public function getProduct(OrderItem $orderItem): Product
    {
        return $orderItem->product;
    }
}
