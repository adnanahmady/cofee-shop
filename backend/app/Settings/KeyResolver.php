<?php

namespace App\Settings;

class KeyResolver
{
    public function __construct(private string $key) {}

    public function resolve(): string
    {
        return preg_replace(
            pattern: $this->getPattern(),
            replacement: '.',
            subject: $this->key
        );
    }

    private function getPattern(): string
    {
        return '/[-_\'"\s\\\\\/\(\)\$\#\^\[\]\*\+]+/';
    }

    public function __toString(): string
    {
        return $this->resolve();
    }
}
