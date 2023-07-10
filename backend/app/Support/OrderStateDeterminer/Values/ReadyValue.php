<?php

namespace App\Support\OrderStateDeterminer\Values;

use App\Support\Values\AbstractValue;
use App\Support\Values\ValueInterface;

class ReadyValue extends AbstractValue implements ValueInterface
{
    public function __toString(): string
    {
        return 'Ready';
    }
}
