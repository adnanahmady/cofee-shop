<?php

namespace App\Settings;

use App\Interfaces\SettingInterface;

class GeneralSetting implements SettingInterface
{
    public function __construct(
        private readonly string $name,
        private readonly null|string $value,
        private readonly null|string $default = null,
    ) {}

    public function name(): string
    {
        return $this->name;
    }

    public function value(): null|string
    {
        return $this->value;
    }

    public function default(): null|string
    {
        return $this->default;
    }

    public function __toString(): string
    {
        return $this->name();
    }
}
