<?php

namespace App\Support\Exchangers;

use App\Api\ApiGetInterface;
use App\Api\Exchanges;
use App\Models\Currency;
use App\ValueObjects\Shared\PriceInterface;
use App\ValueObjects\Shared\PriceObject;

class PriceExchanger
{
    private ApiGetInterface $exchanges;

    public function __construct(readonly private PriceInterface $price)
    {
        $this->exchanges = new Exchanges();
    }

    public function exchange(Currency $to): PriceInterface
    {
        $exchanges = $this->exchanges;
        $code = $this->price->getCurrency()->getCode();

        if ($code === $to->getCode()) {
            return $this->price;
        }
        $basedOnBase = $this->price->getPrice() * $exchanges->getRate($code);
        $exchanged = $basedOnBase / $exchanges->getRate($to->getCode());

        return new PriceObject($exchanged, $to);
    }
}
