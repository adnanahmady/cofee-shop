<?php

namespace App\ValueObjects\Products;

use App\Models\Currency;

interface PriceInterface
{
    public function getPrice(): string;

    public function getCurrency(): Currency;

    public function getFullForm(): string;

    public function __toString(): string;
}
