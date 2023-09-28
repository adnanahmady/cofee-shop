<?php

namespace App\Support\OrderStateDeterminer;

use App\Support\OrderStateDeterminer\Contracts\ChoiceHolderInterface;
use App\Support\OrderStateDeterminer\Contracts\DeterminerInterface;
use App\Support\OrderStateDeterminer\States\WaitingState;
use App\Support\Values\ValueInterface;

class Determiner implements DeterminerInterface
{
    public function __construct(
        public readonly ChoiceHolderInterface $choiceHolder,
        public readonly null|string $currentState
    ) {}

    public function determine(): ValueInterface
    {
        return (new WaitingState($this))->determine();
    }
}
