<?php

namespace App\ValueObjects\Shared;

use App\Models\Currency;

interface PriceInterface
{
    public function getPrice(): int|float;

    public function getCurrency(): Currency;

    public function represent(): string;

    public function __toString(): string;

    public function isInSameCurrency(PriceInterface $price): bool;

    public function isEqual($price): bool;

    public function sum(PriceInterface $price): PriceInterface;
}
