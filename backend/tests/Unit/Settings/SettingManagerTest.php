<?php

namespace Tests\Unit\Settings;

use App\Exceptions\MissingSettingException;
use App\Interfaces\SettingInterface;
use App\Models\Setting;
use App\Settings\SettingManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(SettingManager::class)]
class SettingManagerTest extends TestCase
{
    use RefreshDatabase;

    // phpcs:ignore
    public function test_when_default_value_is_present_it_should_be_returned_instead_of_throwing_an_exception(): void
    {
        $manager = new SettingManager();

        $setting = $manager->get(key: 'empty.setting', default: $default = 80);

        $this->assertSame($setting->value(), (string) $default);
    }

    // phpcs:ignore
    public function test_proper_exception_needs_to_thrown_when_not_having_the_setting(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(MissingSettingException::class);

        $manager = new SettingManager();
        $maximumRate = $this->getMaximumRateSetting();

        $manager->get(key: $maximumRate->name());
    }

    public function test_it_should_return_the_setting_based_on_given_key(): void
    {
        $manager = new SettingManager();
        $maximumRate = $this->getMaximumRateSetting();
        $manager->set(setting: $maximumRate);

        $setting = $manager->get(key: $maximumRate->name());

        $this->assertInstanceOf(SettingInterface::class, $setting);
        $this->assertSame($maximumRate->name(), $setting->name());
        $this->assertSame($maximumRate->value(), $setting->value());
    }

    public function test_it_should_store_settings_in_target_storage(): void
    {
        $manager = new SettingManager();

        $manager->set($setting = $this->getMaximumRateSetting());

        $this->assertDatabaseHas(Setting::TABLE, [
            Setting::KEY => $setting->name(),
            Setting::VALUE => $setting->value(),
        ]);
    }

    private function getMaximumRateSetting(): SettingInterface
    {
        return new class () implements SettingInterface {
            public function name(): string
            {
                return 'maximum.rate';
            }

            public function value(): string
            {
                return 5;
            }
        };
    }
}
