<?php

namespace App\DataTransferObjects\Api;

// phpcs:disable PSR1.Files.SideEffects
final readonly class RateDto
{
    public function __construct(private string $code, private mixed $rate) {}

    public function code(): string
    {
        return $this->code;
    }

    public function rate(): mixed
    {
        return $this->rate;
    }
}
