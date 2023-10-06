<?php

namespace App\Settings;

use App\ExceptionMessages\MissingSettingExceptionMessage;
use App\Exceptions\MissingSettingException;
use App\Interfaces\SettingInterface;
use App\Repositories\SettingRepository;

class SettingManager
{
    private readonly SettingRepository $repository;

    public function __construct(
        private readonly SettingContainerInterface $container
    ) {
        $this->repository = new SettingRepository();
    }

    public function set(SettingInterface $setting): SettingInterface
    {
        $storedSetting = $this->repository->set(
            key: $setting->name(),
            value: $setting->value()
        );

        return new GeneralSetting(
            name: $name = $storedSetting->getKey(),
            value: $storedSetting->getValue(),
            default: $this->container->find($name)?->default(),
        );
    }

    public function get(string $key, string $default = null): SettingInterface
    {
        $setting = $this->repository->get(key: $key);
        $isMissing = null === $setting;

        if ($isMissing) {
            return $this->getDefaultOrRegistered(key: $key, default: $default);
        }

        return new GeneralSetting(
            name: $setting->getKey(),
            value: $setting->getValue(),
            default: $this->container->find($key)?->default()
        );
    }

    private function getDefaultOrRegistered(
        string $key,
        string $default = null
    ): SettingInterface {
        $isRegistered = $this->container->isRegistered(key: $key);
        $hasNoDefault = null === $default && !$isRegistered;

        MissingSettingException::throwIf(
            $hasNoDefault,
            new MissingSettingExceptionMessage()
        );
        $isDefaultPassed = null !== $default;

        if ($isDefaultPassed) {
            return new GeneralSetting(name: $key, value: $default);
        }

        return $this->container->find($key);
    }

    public function register(SettingInterface $setting): void
    {
        $this->container->register($setting);
    }

    public function getSettings(): array
    {
        return array_map(
            fn(SettingInterface $s) => new GeneralSetting(
                name: $s->name(),
                value: $this->repository->get(key: $s->name())?->getValue(),
                default: $s->default()
            ),
            $this->container->all()
        );
    }
}
