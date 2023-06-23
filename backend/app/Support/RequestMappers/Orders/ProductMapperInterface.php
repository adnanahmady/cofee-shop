<?php

namespace App\Support\RequestMappers\Orders;

use App\Models\Product;

interface ProductMapperInterface
{
    public function getProduct(): Product;

    public function getAmount(): int;
}
