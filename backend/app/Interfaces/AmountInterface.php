<?php

namespace App\Interfaces;

interface AmountInterface
{
    public function getAmount(): int;

    public function setAmount(int $amount): void;
}
