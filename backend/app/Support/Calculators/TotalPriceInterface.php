<?php

namespace App\Support\Calculators;

use App\Models\Currency;
use App\ValueObjects\Shared\PriceInterface;

interface TotalPriceInterface
{
    public function addPrices(PriceInterface $price, int $count = 1): self;

    public function addPrice(PriceInterface $price): self;

    public function getPrice(): int|float;

    public function getCurrency(): Currency;

    public function represent(): string|int;

    public function __toString(): string;
}
