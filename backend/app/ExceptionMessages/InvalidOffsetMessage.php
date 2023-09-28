<?php

namespace App\ExceptionMessages;

class InvalidOffsetMessage implements ExceptionMessageInterface
{
    public function __construct(private readonly string $offset) {}

    public function getMessage(): string
    {
        return __(
            'The specified offset ":offset" to access is invalid.',
            ['offset' => $this->offset]
        );
    }

    public function __toString(): string
    {
        return $this->getMessage();
    }
}
