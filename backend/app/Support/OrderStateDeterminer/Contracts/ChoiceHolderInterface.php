<?php

namespace App\Support\OrderStateDeterminer\Contracts;

interface ChoiceHolderInterface
{
    public function isApprovedToRollback(): bool;

    public function isApprovedToForward(): bool;
}
