<?php

namespace App\Support\Converters;

final class PriceDeNormalizer
{
    public function __construct(
        private readonly int|float $price,
        private readonly int $decimalPlaces,
    ) {}

    public function denormalize(): int
    {
        return (int) (
            $this->price * $this->getDecimalIdentifier($this->decimalPlaces)
        );
    }

    private function getDecimalIdentifier(int $decimalPlaces): int
    {
        return pow(10, $decimalPlaces);
    }
}
