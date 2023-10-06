<?php

namespace App\DataTransferObjects\Api;

class RateDto
{
    public function __construct(
        private readonly string $code,
        private readonly mixed $rate,
    ) {}

    public function code(): string
    {
        return $this->code;
    }

    public function rate(): mixed
    {
        return $this->rate;
    }
}
