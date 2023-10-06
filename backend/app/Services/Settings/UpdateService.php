<?php

namespace App\Services\Settings;

use App\Settings\GeneralSetting;
use App\Settings\KeyResolver;
use App\Settings\SettingContainerInterface;
use App\Settings\SettingManager;
use Illuminate\Http\Request;

class UpdateService
{
    private array $storedSettings = [];

    public function __construct(
        private readonly Request $request,
        private readonly SettingManager $manager,
        private readonly SettingContainerInterface $container,
    ) {}

    public function update(): array
    {
        foreach ($this->request->all() as $setting => $value) {
            $this->setSetting($setting, $value);
        }

        return $this->storedSettings;
    }

    private function setSetting(string $setting, mixed $value): void
    {
        $name = new KeyResolver($setting);

        if (!$this->container->find($name)) {
            return;
        }
        $this->storedSettings[] = $this->manager->set(
            new GeneralSetting(name: $name, value: $value)
        );
    }
}
