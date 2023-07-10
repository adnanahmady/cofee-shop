<?php

namespace App\Support\OrderStateDeterminer\States;

use App\Support\OrderStateDeterminer\Contracts\DeterminerInterface;
use App\Support\OrderStateDeterminer\Values\DeliveredValue;
use App\Support\OrderStateDeterminer\Values\PreparationValue;
use App\Support\OrderStateDeterminer\Values\ReadyValue;
use App\Support\Values\ValueInterface;

class ReadyState extends AbstractState implements DeterminerInterface
{
    protected function determineState(): ValueInterface
    {
        if ($this->wantsThisState()) {
            return new ReadyValue();
        }

        return $this->passToNextState();
    }

    private function hasDemandedForThisState(): bool
    {
        return $this->isApprovedToForward() && $this->hasPreviousState();
    }

    private function hasPreviousState(): bool
    {
        return (new PreparationValue())->isEqual($this->getCurrentState());
    }

    private function isApprovedToRollbackToThisState(): bool
    {
        return $this->isApprovedToGoBackward() && $this->hasNextState();
    }

    private function hasNextState(): bool
    {
        return (new DeliveredValue())->isEqual($this->getCurrentState());
    }

    private function passToNextState(): ValueInterface
    {
        $nextState = new DeliveredState($this->determiner);

        return $nextState->determine();
    }

    private function wantsThisState(): bool
    {
        return $this->hasDemandedForThisState()
            || $this->isApprovedToRollbackToThisState();
    }
}
