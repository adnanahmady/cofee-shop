<?php

namespace Tests\Unit\StateDeterminer;

use App\Support\OrderStateDeterminer\Contracts\ChoiceHolderInterface;

class ChoiceHolder implements ChoiceHolderInterface
{
    public function __construct(
        private readonly bool $forward = false,
        private readonly bool $rollback = false,
    ) {}

    public function isApprovedToRollback(): bool
    {
        return $this->rollback;
    }

    public function isApprovedToForward(): bool
    {
        return $this->forward;
    }
}
