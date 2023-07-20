<?php

namespace App\Traits\Models\Fields;

use App\Contracts\Models\Fields\AmountContract;

trait HasAmountTrait
{
    public function getAmount(): int
    {
        return $this->{AmountContract::AMOUNT};
    }

    public function setAmount(int $amount): void
    {
        $this->{AmountContract::AMOUNT} = $amount;
    }
}
