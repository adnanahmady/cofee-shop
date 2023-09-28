<?php

namespace Tests\Feature\Orders;

use App\Http\Requests\Api\V1\Orders\StoreRequest;
use App\Models\Address;
use App\Models\DeliveryType;
use App\Models\Option;
use App\Models\Product;

class OrderMaker
{
    private array $selectedProducts = [];
    private DeliveryType $deliveryType;
    private Address $address;

    public function __construct()
    {
        $this->setDeliveryType(createDeliveryType());
    }

    public function make(): array
    {
        return [
            StoreRequest::PRODUCTS => $this->selectedProducts,
            StoreRequest::DELIVERY_TYPE => $this->deliveryType->getId(),
            StoreRequest::ADDRESS => $this->address->getId(),
        ];
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function setDeliveryType(DeliveryType $value): void
    {
        $this->deliveryType = $value;
    }

    /**
     * @param array<Option>|null $selectedOptions
     */
    public function createItem(
        int $orderedAmount = 1,
        int $productAmount = 10,
        Product $product = null,
        array $selectedOptions = null
    ): Product {
        $product ??= $this->makeProduct($productAmount);
        $this->selectedProducts[] = $this->makeItem(
            product: $product,
            amount: $orderedAmount,
            selectedOptions: $selectedOptions
        );

        return $product;
    }

    private function makeProduct(int $amount): Product
    {
        return createProduct([Product::AMOUNT => $amount]);
    }

    /**
     * @param array<Option>|null $selectedOptions
     */
    private function makeItem(
        Product $product,
        int $amount,
        array $selectedOptions = null,
    ): array {
        return [
            StoreRequest::PRODUCT_ID => $product->getId(),
            StoreRequest::AMOUNT => $amount,
        ] + ($selectedOptions ? [
            StoreRequest::CUSTOMIZATIONS => $this
                ->createCustomizations($selectedOptions),
        ] : []);
    }

    /** @param array<Option>|null $selectedOptions */
    public function createCustomizations(
        array $selectedOptions = null
    ): array|null {
        return $selectedOptions ? array_map(fn(Option $o) => [
            StoreRequest::OPTION_ID => $o->getId(),
        ], $selectedOptions) : null;
    }
}
