<?php

namespace App\Support\RequestMappers\Orders;

use App\ExceptionMessages\InvalidValueMessage;
use App\Exceptions\InvalidValueException;
use App\Http\Requests\Api\V1\Orders\StoreRequest;
use App\Models\Product;
use App\Repositories\ProductRepository;

class ProductMapper implements ProductMapperInterface
{
    private ProductRepository $productRepository;

    public function __construct(readonly private array $item)
    {
        $this->productRepository = new ProductRepository();
    }

    public function getProduct(): Product
    {
        return $this->productRepository->findOrFail(
            $this->item[StoreRequest::PRODUCT_ID]
        );
    }

    public function getAmount(): int
    {
        return $this->item[StoreRequest::AMOUNT] ?? 1;
    }

    public function getCustomizations(): CustomizationsIterator
    {
        if (!key_exists(StoreRequest::CUSTOMIZATIONS, $this->item)) {
            return new CustomizationsIterator([]);
        }
        InvalidValueException::throwUnless(
            is_array($this->item[StoreRequest::CUSTOMIZATIONS]),
            new InvalidValueMessage()
        );

        return new CustomizationsIterator(
            $this->item[StoreRequest::CUSTOMIZATIONS] ?? []
        );
    }
}
