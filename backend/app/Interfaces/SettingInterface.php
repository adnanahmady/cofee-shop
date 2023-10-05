<?php

namespace App\Interfaces;

interface SettingInterface
{
    /**
     * Each setting needs to have a name,
     * so it will be accessed and stored
     * based on that name.
     */
    public function name(): string;

    /**
     * Each setting needs to contain
     * some value, what ever the value
     * is, it needs to be presented as
     * string.
     */
    public function value(): string;
}
