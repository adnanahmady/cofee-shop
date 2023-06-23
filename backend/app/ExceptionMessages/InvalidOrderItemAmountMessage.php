<?php

namespace App\ExceptionMessages;

use App\Http\Requests\Api\V1\Orders\StoreRequest;
use App\Models\Product;

class InvalidOrderItemAmountMessage implements ViolationMessageInterface
{
    public function __construct(readonly private Product $product)
    {
    }

    public function getMessage(): string
    {
        return __(
            'More than the existing amount for product ":name" requested!',
            ['name' => $this->product->getName()]
        );
    }

    public function toArray(): array
    {
        return [
            (new StoreRequest())->getProductIdPath() => [
                $this->getMessage(),
            ],
        ];
    }

    public function __toString(): string
    {
        return $this->getMessage();
    }
}
