<?php

namespace App\ValueObjects\Users;

class NameObject implements NameInterface
{
    public function __construct(
        private string $firstName,
        private string $lastName
    ) {}

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFullName(): string
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }

    public function __toString(): string
    {
        return $this->getFullName();
    }
}
