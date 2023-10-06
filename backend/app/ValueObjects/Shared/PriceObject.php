<?php

namespace App\ValueObjects\Shared;

use App\Models\Currency;
use App\Repositories\CurrencyRepository;
use App\Settings\Delegators\MainCurrency;
use App\Settings\SettingManager;
use App\Support\Converters\PriceDeNormalizer;
use App\Support\Converters\PriceNormalizer;
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
        $manager = $this->getManager();
        $mainCurrency = $manager->get(new MainCurrency());
        $doesRequireExchangeToMainCurrency = (
            null !== $mainCurrency->value()
            && $mainCurrency->value() !== $this->currency->getCode()
        );

        if ($doesRequireExchangeToMainCurrency) {
            $r = new CurrencyRepository();
            $obj = $this->exchange(
                $this,
                $r->findByCode($mainCurrency->value())
            );

            return $obj->represent();
        }

        return sprintf(
            '%s %s',
            $this->currency->getCode(),
            $this->getFormedPrice()
        );
    }

    private function getManager(): SettingManager
    {
        return resolve(SettingManager::class);
    }

    /**
     * The price object can represent its value
     * without decimal applied form.
     *
     * Attention: be-careful with this functionality
     * because it can deform the price.
     */
    public function toInteger(): int
    {
        $deNormalizer = new PriceDeNormalizer(
            price: $this->price,
            decimalPlaces: $this->currency->getDecimalPlaces()
        );

        return $deNormalizer->denormalize();
    }

    /**
     * The price object can represent its value
     * in decimal applied form.
     *
     * Attention: be-careful with this functionality
     * because it can deform the price.
     */
    public function toNormalForm(): int|float
    {
        $normalizer = new PriceNormalizer(
            price: $this->price,
            decimalPlaces: $this->currency->getDecimalPlaces()
        );

        return $normalizer->normalize();
    }

    private function getFormedPrice(): int|float
    {
        $decimalPlaces = $this->getCurrency()->getDecimalPlaces();

        if (0 === $decimalPlaces) {
            return (int) $this->getPrice();
        }

        return $this->toNormalForm();
    }

    public function isInSameCurrency(PriceInterface $price): bool
    {
        return $this->currency->getId() === $price->getCurrency()->getId();
    }

    public function isEqual(PriceInterface $price): bool
    {
        return $this->getPrice() === $price->getPrice()
            && $this->isInSameCurrency($price);
    }

    public function sum(PriceInterface $price): PriceInterface
    {
        $exchangedPrice = $this->exchange($price, $this->currency);

        return new static(
            $this->getPrice() + $exchangedPrice->getPrice(),
            $this->currency
        );
    }

    private function exchange(
        PriceInterface $price,
        Currency $currency
    ): PriceInterface {
        $exchanger = new PriceExchanger($price);

        return $exchanger->exchange(to: $currency);
    }

    public function __toString(): string
    {
        return $this->represent();
    }
}
