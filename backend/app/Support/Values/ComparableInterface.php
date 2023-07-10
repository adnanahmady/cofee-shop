<?php

namespace App\Support\Values;

interface ComparableInterface
{
    /**
     * Checks the values to be exactly the same
     * values.
     *
     * @param ValueInterface $value value
     */
    public function isSame(ValueInterface $value): bool;

    /**
     * Checks the values for their text representation.
     *
     * @param string $value value
     */
    public function isEqual(string $value): bool;
}
