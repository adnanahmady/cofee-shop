<?php

namespace App\Support\RequestMappers\Orders;

use App\Models\Product;

interface ProductMapperInterface
{
    public function getProduct(): Product;

    public function getAmount(): int;

    /**
     * The product can have its own customization,
     * this means a product can have many or any.
     */
    public function getCustomizations(): CustomizationsIterator;
}
