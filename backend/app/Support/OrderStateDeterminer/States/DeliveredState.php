<?php

namespace App\Support\OrderStateDeterminer\States;

use App\Support\OrderStateDeterminer\Contracts\DeterminerInterface;
use App\Support\OrderStateDeterminer\Values\DeliveredValue;
use App\Support\OrderStateDeterminer\Values\PreparationValue;
use App\Support\OrderStateDeterminer\Values\ReadyValue;
use App\Support\Values\ValueInterface;

class DeliveredState extends AbstractState implements DeterminerInterface
{
    protected function determineState(): ValueInterface
    {
        if ($this->isApprovedToRollback()) {
            return $this->passToPreviousState();
        }

        if ($this->isAUnknownState()) {
            return new PreparationValue();
        }

        return new DeliveredValue();
    }

    private function isApprovedToRollback(): bool
    {
        return !$this->isApprovedToForward()
            && $this->isApprovedToGoBackward()
            && (new DeliveredValue())->isEqual($this->getCurrentState());
    }

    private function passToPreviousState(): ValueInterface
    {
        $previousState = new ReadyState($this->determiner);

        return $previousState->determine();
    }

    private function isAUnknownState(): bool
    {
        return !(new ReadyValue())->isEqual($this->getCurrentState())
            && !(new DeliveredValue())->isEqual($this->getCurrentState());
    }
}
