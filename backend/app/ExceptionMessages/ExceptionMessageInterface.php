<?php

namespace App\ExceptionMessages;

interface ExceptionMessageInterface
{
    public function getMessage(): string;

    public function __toString(): string;
}
