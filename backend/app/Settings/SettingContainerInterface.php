<?php

namespace App\Settings;

use App\Interfaces\SettingInterface;

interface SettingContainerInterface
{
    public function find(string $key): null|SettingInterface;

    public function isRegistered(string $key): bool;

    public function register(SettingInterface $setting): void;

    public function all(): array;
}
