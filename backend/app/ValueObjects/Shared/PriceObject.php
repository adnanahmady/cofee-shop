<?php

namespace App\ValueObjects\Shared;

use App\Models\Currency;
use App\Support\Exchangers\PriceExchanger;

class PriceObject implements PriceInterface
{
    private readonly Currency $currency;

    public function __construct(
        private readonly int|float $price,
        Currency|int $currency
    ) {
        $this->currency = is_int($currency) ?
            Currency::findOrFail($currency) :
            $currency;
    }

    public function getPrice(): int|float
    {
        return $this->price;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function represent(): string
    {
        return sprintf(
            '%s %s',
            $this->currency->getCode(),
            $this->getFormedPrice()
        );
    }

    private function getFormedPrice(): int|float
    {
        $decimalPlaces = $this->getCurrency()->getDecimalPlaces();

        if (0 === $decimalPlaces) {
            return (int) $this->getPrice();
        }

        return round($this->getPrice(), $decimalPlaces);
    }

    public function isInSameCurrency(PriceInterface $price): bool
    {
        return $this->currency->getId() === $price->getCurrency()->getId();
    }

    public function isEqual($price): bool
    {
        return $this->getPrice() === $price->getPrice()
            && $this->isInSameCurrency($price);
    }

    public function sum(PriceInterface $price): PriceInterface
    {
        $exchangedPrice = $this->exchange($price);

        return new static(
            $this->getPrice() + $exchangedPrice->getPrice(),
            $this->currency
        );
    }

    private function exchange(PriceInterface $price): PriceInterface
    {
        $exchanger = new PriceExchanger($price);

        return $exchanger->exchange(to: $this->currency);
    }

    public function __toString(): string
    {
        return $this->represent();
    }
}
