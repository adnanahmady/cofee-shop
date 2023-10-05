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
        private readonly SettingContainerInterface $registers
    ) {
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
        $isMissing = null === $setting;

        if ($isMissing) {
            return $this->getDefaultOrRegistered(key: $key, default: $default);
        }

        return new GeneralSetting(
            name: $setting->getKey(),
            value: $setting->getValue(),
            default: $this->registers->find($key)?->default()
        );
    }

    private function getDefaultOrRegistered(
        string $key,
        string $default = null
    ): SettingInterface {
        $isRegistered = $this->registers->isRegistered(key: $key);
        $hasNoDefault = null === $default && !$isRegistered;

        MissingSettingException::throwIf(
            $hasNoDefault,
            new MissingSettingExceptionMessage()
        );
        $isDefaultPassed = null !== $default;

        if ($isDefaultPassed) {
            return new GeneralSetting(name: $key, value: $default);
        }

        return $this->registers->find($key);
    }

    public function register(SettingInterface $setting): void
    {
        $this->registers->register($setting);
    }

    public function getSettings(): array
    {
        return array_map(
            fn(SettingInterface $s) => new GeneralSetting(
                name: $s->name(),
                value: $this->repository->get(key: $s->name())?->getValue(),
                default: $s->default()
            ),
            $this->registers->all()
        );
    }
}
