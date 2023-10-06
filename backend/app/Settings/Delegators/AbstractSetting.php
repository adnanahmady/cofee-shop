<?php

namespace App\Settings\Delegators;

use App\Interfaces\SettingInterface;

abstract class AbstractSetting implements SettingInterface
{
    public function __construct(private readonly null|string $value = null) {}

    final public function value(): null|string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->name();
    }
}
