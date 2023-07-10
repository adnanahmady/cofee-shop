<?php

namespace App\Support\Values;

abstract class AbstractValue implements ComparableInterface
{
    public function isSame(ValueInterface $value): bool
    {
        return $this::class === $value::class;
    }

    public function isEqual(string $value): bool
    {
        return (string) $this === $value;
    }
}
