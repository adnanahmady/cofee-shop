<?php

namespace App\ValueObjects\Products;

use App\Models\Currency;

class PriceObject implements PriceInterface
{
    private Currency $currency;

    public function __construct(
        readonly private int $price,
        int $currency
    ) {
        $this->currency = Currency::findOrFail($currency);
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getFullForm(): string
    {
        return sprintf('%s %s', $this->currency->getCode(), $this->price);
    }

    public function __toString(): string
    {
        return $this->getFullForm();
    }
}
