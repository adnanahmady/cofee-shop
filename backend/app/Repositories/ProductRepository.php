<?php

namespace App\Repositories;

use App\ExceptionMessages\InvalidOrderItemAmountMessage;
use App\Exceptions\Models\InvalidOrderItemAmountException;
use App\Models\Customization;
use App\Models\Product;
use App\ValueObjects\Shared\PriceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class ProductRepository
{
    public function addCustomization(
        Product $product,
        Customization $customization
    ): Model|Customization {
        return $product->customizations()->save($customization);
    }

    public function getCustomizations(Product $product): Collection
    {
        return $product->customizations;
    }

    public function getAvailable(): Collection
    {
        return Product::where(Product::AMOUNT, '>', 0)->get();
    }

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
