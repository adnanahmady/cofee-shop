<?php

namespace App\Support\OrderStateDeterminer\States;

use App\Support\OrderStateDeterminer\Contracts\DeterminerInterface;
use App\Support\OrderStateDeterminer\Values\PreparationValue;
use App\Support\OrderStateDeterminer\Values\WaitingValue;
use App\Support\Values\ValueInterface;

class WaitingState extends AbstractState implements DeterminerInterface
{
    protected function determineState(): ValueInterface
    {
        if ($this->wantsRollbackFromNextState()) {
            return new WaitingValue();
        }

        if ($this->wantsNextState()) {
            return $this->passToNextState();
        }

        return new WaitingValue();
    }

    private function wantsNextState(): bool
    {
        return $this->isApprovedToGoForward()
            || $this->wantsToRollbackFromFurtherStates();
    }

    private function isApprovedToGoForward(): bool
    {
        return $this->isApprovedToForward() && $this->getCurrentState();
    }

    private function wantsToRollbackFromFurtherStates(): bool
    {
        return $this->isApprovedToGoBackward()
            && !(new WaitingValue())->isEqual($this->getCurrentState());
    }

    private function wantsRollbackFromNextState(): bool
    {
        return $this->isApprovedToGoBackward()
            && (new PreparationValue())->isEqual($this->getCurrentState());
    }

    private function passToNextState(): ValueInterface
    {
        $nextState = new PreparationState($this->determiner);

        return $nextState->determine();
    }
}
