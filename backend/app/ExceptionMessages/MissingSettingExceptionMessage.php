<?php

namespace App\ExceptionMessages;

class MissingSettingExceptionMessage implements ExceptionMessageInterface
{
    public function getMessage(): string
    {
        return __(
            'The specified setting does not exist, ' .
            'either define some default value or set it.'
        );
    }

    public function __toString(): string
    {
        return $this->getMessage();
    }
}
