<?php

namespace App\Support\RequestMappers\Orders;

use App\Models\Product;

interface DataMapperInterface
{
    public function getProduct(): Product;

    public function getAmount(): int;

    public function getCustomizations(): CustomizationsIterator;
}
