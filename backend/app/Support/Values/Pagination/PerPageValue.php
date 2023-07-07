<?php

namespace App\Support\Values\Pagination;

use App\Support\Values\IntegerValueInterface;

class PerPageValue implements IntegerValueInterface
{
    private int $value = 10;

    public function __toString(): string
    {
        return $this->value;
    }

    public function represent(): int
    {
        return $this->value;
    }
}
