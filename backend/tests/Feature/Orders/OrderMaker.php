<?php

namespace Tests\Feature\Orders;

use App\Http\Requests\Api\V1\Orders\StoreRequest;
use App\Models\Product;

class OrderMaker
{
    private array $items = [];
    private mixed $deliveryType;

    public function __construct()
    {
        $this->setDeliveryType(
            createDeliveryType()->getId()
        );
    }

    public function make(): array
    {
        return [
            StoreRequest::PRODUCTS => $this->items,
            StoreRequest::DELIVERY_TYPE => $this->deliveryType,
        ];
    }

    public function setDeliveryType(mixed $value): void
    {
        $this->deliveryType = $value;
    }

    public function createItem(int $orderedAmount = 1, int $productAmount = 10): Product
    {
        $product = $this->makeProduct($productAmount);
        $this->items[] = $this->makeItem(product: $product, amount: $orderedAmount);

        return $product;
    }

    private function makeProduct(int $amount): Product
    {
        return createProduct([Product::AMOUNT => $amount]);
    }

    private function makeItem(Product $product, int $amount): array
    {
        return [
            StoreRequest::PRODUCT_ID => $product->getId(),
            StoreRequest::AMOUNT => $amount,
        ];
    }
}
