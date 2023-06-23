<?php

namespace App\Support\Calculators;

use App\Models\Currency;
use App\ValueObjects\Shared\PriceInterface;

class TotalPrice implements TotalPriceInterface
{
    private null|PriceInterface $price = null;
    private null|Currency $currency = null;

    public function addPrices(PriceInterface $price, int $count = 1): self
    {
        for ($i = 0; $i < $count; ++$i) {
            $this->addPrice($price);
        }

        return $this;
    }

    public function addPrice(PriceInterface $price): self
    {
        if (null === $this->price) {
            $this->price = $price;

            return $this;
        }
        $this->price = $this->price->sum($price);

        return $this;
    }

    public function getPrice(): int|float
    {
        return $this->price->getPrice();
    }

    public function getCurrency(): Currency
    {
        return $this->currency ?? $this->price->getCurrency();
    }

    public function represent(): string|int
    {
        return $this->price?->represent() ?? 0;
    }

    public function __toString(): string
    {
        return $this->price->represent();
    }
}
