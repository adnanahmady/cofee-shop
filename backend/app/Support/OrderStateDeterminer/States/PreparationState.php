<?php

namespace App\Support\OrderStateDeterminer\States;

use App\Support\OrderStateDeterminer\Contracts\DeterminerInterface;
use App\Support\OrderStateDeterminer\Values\PreparationValue;
use App\Support\OrderStateDeterminer\Values\ReadyValue;
use App\Support\OrderStateDeterminer\Values\WaitingValue;
use App\Support\Values\ValueInterface;

class PreparationState extends AbstractState implements DeterminerInterface
{
    protected function determineState(): ValueInterface
    {
        if ($this->wantsToRollbackToThisState()) {
            return new PreparationValue();
        }

        if ($this->wantsToRollback()) {
            return $this->passToPreviousState();
        }

        if ($this->hasDemandedForOtherStates()) {
            return $this->passToNextState();
        }

        return new PreparationValue();
    }

    private function hasNextState(): bool
    {
        return (new ReadyValue())->isEqual($this->getCurrentState());
    }

    private function wantsToRollback(): bool
    {
        return $this->isApprovedToGoBackward() && $this->isSameState();
    }

    private function isSameState(): bool
    {
        return (new PreparationValue())->isEqual($this->getCurrentState());
    }

    private function hasDemandedForOtherStates(): bool
    {
        return !$this->isPreviousState();
    }

    private function isPreviousState(): bool
    {
        return (new WaitingValue())->isEqual($this->getCurrentState());
    }

    private function passToPreviousState(): ValueInterface
    {
        $previousState = new WaitingState($this->determiner);

        return $previousState->determine();
    }

    private function passToNextState(): ValueInterface
    {
        $nextState = new ReadyState($this->determiner);

        return $nextState->determine();
    }

    private function wantsToRollbackToThisState(): bool
    {
        return $this->isApprovedToGoBackward() && $this->hasNextState();
    }
}
