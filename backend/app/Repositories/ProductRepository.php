<?php

namespace App\Repositories;

use App\Models\Product;
use App\ValueObjects\Products\PriceInterface;

class ProductRepository
{
    public function create(string $name, PriceInterface $price): Product
    {
        $product = new Product();
        $product->setName($name);
        $product->setPriceObject($price);
        $product->save();

        return $product;
    }
}
