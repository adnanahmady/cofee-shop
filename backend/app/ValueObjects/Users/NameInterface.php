<?php

namespace App\ValueObjects\Users;

interface NameInterface
{
    public function getFirstName(): string;

    public function getLastName(): string;

    public function getFullName(): string;

    public function __toString(): string;
}
