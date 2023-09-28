<?php

namespace App\Support\RequestMappers\Orders;

use App\Models\Option;

final class CustomizationDto
{
    public function __construct(
        private readonly Option $option,
    ) {}

    public function getOption(): Option
    {
        return $this->option;
    }

    public function getOptionId(): int
    {
        return $this->option->getId();
    }
}
