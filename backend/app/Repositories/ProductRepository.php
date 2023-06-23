<?php

namespace App\Repositories;

use App\ExceptionMessages\InvalidOrderItemAmountMessage;
use App\Exceptions\Models\InvalidOrderItemAmountException;
use App\Models\Product;
use App\ValueObjects\Shared\PriceInterface;

class ProductRepository
{
    public function create(
        string $name,
        int $amount,
        PriceInterface $price
    ): Product {
        $product = new Product();
        $product->setName($name);
        $product->setAmount($amount);
        $product->setPriceObject($price);
        $product->save();

        return $product;
    }

    public function findOrFail(int $productId): Product
    {
        return Product::findOrFail($productId);
    }

    public function orderProduct(Product $product, int $amount): Product
    {
        $remains = $product->getAmount() - $amount;
        $product->update([Product::AMOUNT => $remains]);
        $product = $product->fresh();

        InvalidOrderItemAmountException::throwIf(
            $product->getAmount() < 0,
            new InvalidOrderItemAmountMessage($product)
        );

        return $product;
    }
}
