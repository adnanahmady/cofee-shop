<?php

namespace App\Traits\Models;

use App\ExceptionMessages\RequiredRelationMessage;
use App\Exceptions\Models\UnavailableRelationException;
use App\Models\Currency;
use App\Support\Converters\PriceDeNormalizer;
use App\Support\Converters\PriceNormalizer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasPriceAndCurrencyTrait
{
    /** Price field name. */
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

        $converter = new PriceDeNormalizer(
            price: $price,
            decimalPlaces: $this->getDecimalPlaces()
        );

        $this->attributes[$this->getPriceName()] = $converter->denormalize();
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
        $price = $this->attributes[$this->getPriceName()];

        $deNormalizer = new PriceNormalizer(
            price: $price,
            decimalPlaces: $this->getDecimalPlaces()
        );

        return $deNormalizer->normalize();
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
