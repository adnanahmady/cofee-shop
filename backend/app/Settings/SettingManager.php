<?php

namespace App\Settings;

use App\ExceptionMessages\MissingSettingExceptionMessage;
use App\Exceptions\MissingSettingException;
use App\Interfaces\SettingInterface;
use App\Repositories\SettingRepository;

class SettingManager
{
    private readonly SettingRepository $repository;

    public function __construct()
    {
        $this->repository = new SettingRepository();
    }

    public function set(SettingInterface $setting): void
    {
        $this->repository->set(
            key: $setting->name(),
            value: $setting->value()
        );
    }

    public function get(string $key, string $default = null): SettingInterface
    {
        $setting = $this->repository->get(key: $key);

        MissingSettingException::throwIf(
            null === $setting && null === $default,
            new MissingSettingExceptionMessage()
        );

        if (null === $setting) {
            return new StoredSetting(name: $key, value: $default);
        }

        return new StoredSetting(
            name: $setting->getKey(),
            value: $setting->getValue()
        );
    }
}
