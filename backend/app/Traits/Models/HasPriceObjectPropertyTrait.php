<?php

namespace App\Traits\Models;

use App\ValueObjects\Shared\PriceInterface;
use App\ValueObjects\Shared\PriceObject;

trait HasPriceObjectPropertyTrait
{
    protected null|PriceInterface $priceObject = null;

    abstract protected function getPriceName(): string;

    abstract protected function getCurrencyName(): string;

    public function getPriceObject(): PriceInterface
    {
        if (null === $this->priceObject) {
            $this->priceObject = new PriceObject(
                $this->{$this->getPriceName()},
                $this->{$this->getCurrencyName()}
            );
        }

        return $this->priceObject;
    }

    public function setPriceObject(PriceInterface $priceObject): self
    {
        $this->priceObject = $priceObject;
        $currency = $priceObject->getCurrency()->getId();
        $this->{$this->getCurrencyName()} = $currency;
        $this->{$this->getPriceName()} = $priceObject->getPrice();

        return $this;
    }
}
