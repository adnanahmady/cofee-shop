<?php

namespace App\ExceptionMessages;

interface ViolationMessageInterface extends ExceptionMessageInterface
{
    public function toArray(): array;
}
