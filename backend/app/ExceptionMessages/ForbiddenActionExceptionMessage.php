<?php

namespace App\ExceptionMessages;

class ForbiddenActionExceptionMessage implements ExceptionMessageInterface
{
    public function getMessage(): string
    {
        return __('You are not authorized to perform this action.');
    }

    public function __toString(): string
    {
        return $this->getMessage();
    }
}
