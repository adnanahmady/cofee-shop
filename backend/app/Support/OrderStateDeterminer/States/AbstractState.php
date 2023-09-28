<?php

namespace App\Support\OrderStateDeterminer\States;

use App\Support\OrderStateDeterminer\Contracts\DeterminerInterface;
use App\Support\OrderStateDeterminer\Determiner;
use App\Support\Values\ValueInterface;

abstract class AbstractState implements DeterminerInterface
{
    public function __construct(
        protected readonly Determiner $determiner
    ) {}

    public function determine(): ValueInterface
    {
        if (null === $this->getCurrentState()) {
            return $this->passToFirstState();
        }

        return $this->determineState();
    }

    protected function getCurrentState(): ?string
    {
        return $this->determiner->currentState;
    }

    protected function passToFirstState(): ValueInterface
    {
        $previousState = new WaitingState($this->determiner);

        return $previousState->determine();
    }

    abstract protected function determineState(): ValueInterface;

    protected function isApprovedToForward(): bool
    {
        return $this->determiner->choiceHolder->isApprovedToForward();
    }

    protected function isApprovedToGoBackward(): bool
    {
        return $this->determiner->choiceHolder->isApprovedToRollback();
    }
}
