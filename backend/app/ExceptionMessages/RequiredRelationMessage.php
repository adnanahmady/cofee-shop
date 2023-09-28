<?php

namespace App\ExceptionMessages;

class RequiredRelationMessage implements ExceptionMessageInterface
{
    public function __construct(readonly private string $relation) {}

    public function getMessage(): string
    {
        return __(
            'You need to set ":relation" first.',
            ['relation' => $this->relation]
        );
    }

    public function __toString(): string
    {
        return $this->getMessage();
    }
}
