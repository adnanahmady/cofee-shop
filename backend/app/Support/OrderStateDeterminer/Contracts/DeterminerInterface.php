<?php

namespace App\Support\OrderStateDeterminer\Contracts;

use App\Support\Values\ValueInterface;

interface DeterminerInterface
{
    public function determine(): ValueInterface;
}
