<?php

namespace App\Settings;

use App\Interfaces\SettingInterface;

class StoredSetting implements SettingInterface
{
    public function __construct(
        private readonly string $name,
        private readonly string $value
    ) {}

    public function name(): string
    {
        return $this->name;
    }

    public function value(): string
    {
        return $this->value;
    }
}
