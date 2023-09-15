<?php

namespace App\ExceptionMessages;

class InvalidValueMessage implements ExceptionMessageInterface
{
    public function getMessage(): string
    {
        return __('The presented value is invalid');
    }

    public function __toString(): string
    {
        return $this->getMessage();
    }
}
