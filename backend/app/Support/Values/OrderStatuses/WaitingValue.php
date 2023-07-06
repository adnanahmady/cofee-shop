<?php

namespace App\Support\Values\OrderStatuses;

use App\Support\Values\ValueInterface;

class WaitingValue implements ValueInterface
{
    public function __toString(): string
    {
        return 'Waiting';
    }
}
