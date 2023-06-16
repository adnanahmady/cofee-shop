<?php

namespace App\ExceptionMessages;

class InvalidEmailMessage implements ViolationMessageInterface
{
    public function __construct(readonly private string $email)
    {
    }

    public function getMessage(): string
    {
        return __(
            'No user exists with ":email" email',
            ['email' => $this->email]
        );
    }

    public function toArray(): array
    {
        return [
            'email' => [$this->getMessage()],
        ];
    }

    public function __toString(): string
    {
        return $this->getMessage();
    }
}
