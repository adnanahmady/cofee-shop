<?php

namespace App\Support\Values;

interface IntegerValueInterface extends ValueInterface
{
    public function represent(): int;
}
