<?php

namespace App\Traits\Models;

use App\ExceptionMessages\RequiredRelationMessage;
use App\Exceptions\Models\UnavailableRelationException;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasPriceAndCurrencyTrait
{
    /**
     * Price field name.
     */
    abstract protected function getPriceName(): string;

    /**
     * Creates an integer value containing the decimal
     * places.
     *
     * @param int|float $price price
     */
    public function setPriceAttribute(int|float $price): void
    {
        UnavailableRelationException::throwIf(
            null === $this->currency,
            new RequiredRelationMessage('currency')
        );

        $this->attributes[$this->getPriceName()] = $this
            ->injectDecimals($price);
    }

    private function injectDecimals(float|int $price): float
    {
        return round($price * $this->getDecimalIdentifier());
    }

    private function getDecimalIdentifier(): int
    {
        return pow(10, $this->getDecimalPlaces());
    }

    private function getDecimalPlaces(): int|null
    {
        $currency = is_int($this->currency) ?
            Currency::find($this->currency) :
            $this->currency;

        return $currency?->getDecimalPlaces();
    }

    /**
     * Exports decimal places of the price value based
     * on the currency.
     */
    public function getPriceAttribute(): int|float
    {
        $price = $this->extractDecimals();

        if (0 === $this->getDecimalPlaces()) {
            return (int) $price;
        }

        return $price;
    }

    private function extractDecimals(): int|float
    {
        $value = $this->attributes[$this->getPriceName()];

        return $value / $this->getDecimalIdentifier();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
