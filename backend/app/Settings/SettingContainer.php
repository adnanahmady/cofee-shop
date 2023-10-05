<?php

namespace App\Settings;

use App\Interfaces\SettingInterface;

class SettingContainer implements SettingContainerInterface
{
    private array $registeredSettings = [];

    public function find(string $key): null|SettingInterface
    {
        return @$this->registeredSettings[$key];
    }

    public function isRegistered(string $key): bool
    {
        return key_exists($key, $this->registeredSettings);
    }

    public function register(SettingInterface $setting): void
    {
        $isRegisteredAlready = key_exists(
            $setting->name(),
            $this->registeredSettings
        );

        if ($isRegisteredAlready) {
            return;
        }
        $this->registeredSettings[$setting->name()] = $setting;
    }

    public function all(): array
    {
        return array_values($this->registeredSettings);
    }
}
