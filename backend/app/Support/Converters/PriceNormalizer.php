<?php

namespace App\Support\Converters;

class PriceNormalizer
{
    public function __construct(
        private readonly int|float $price,
        private readonly int $decimalPlaces,
    ) {
    }

    public function normalize(): int|float
    {
        $price = $this->buildFinalForm();

        if (0 === $this->decimalPlaces) {
            return (int) $price;
        }

        return $price;
    }

    private function buildFinalForm(): float
    {
        $price = $this->price;

        if (is_float($price)) {
            $m = $this->extractCorrectPrice();

            if (count($m)) {
                return $m[0];
            }
            $price = $this->cleanThePrice();
        }

        return (float) join('', $this->separateDecimalPart($price));
    }

    private function extractCorrectPrice(): array
    {
        preg_match(
            sprintf('/\d+\.\d{%s}/', $this->decimalPlaces),
            $this->price,
            $m
        );

        return $m;
    }

    private function cleanThePrice(): string
    {
        return rtrim(str_replace('.', '', $this->price), '0');
    }

    private function separateDecimalPart(string $price): array
    {
        $price = str_split($price);
        array_splice(
            $price,
            $this->getSeparationPoint($price),
            0,
            '.'
        );

        return $price;
    }

    private function getSeparationPoint(array $price): int
    {
        return count($price) - $this->decimalPlaces;
    }
}
