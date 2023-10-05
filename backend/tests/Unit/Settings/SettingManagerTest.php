<?php

namespace Tests\Unit\Settings;

use App\Exceptions\MissingSettingException;
use App\Interfaces\SettingInterface;
use App\Models\Setting;
use App\Settings\SettingContainer;
use App\Settings\SettingManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\CoversClass;
use Tests\TestCase;

#[CoversClass(SettingManager::class)]
class SettingManagerTest extends TestCase
{
    use RefreshDatabase;

    // phpcs:ignore
    public function test_when_getting_stored_setting_the_registered_needs_to_have_its_default(): void
    {
        $maximumRate = $this->getMaximumRateSetting();
        $manager = $this->createManager();
        $manager->register(setting: $maximumRate);
        $storedSetting = createSetting([
            Setting::KEY => $maximumRate->name(),
        ]);

        $setting = $manager->get(key: $maximumRate->name());

        $this->assertSame($storedSetting->getValue(), $setting->value());
        $this->assertSame($storedSetting->getKey(), $setting->name());
        $this->assertSame($maximumRate->default(), $setting->default());
    }

    public function test_each_setting_should_register_only_once(): void
    {
        $manager = $this->createManager();
        $maximumRate = $this->getMaximumRateSetting(rate: 5);
        $manager->register(setting: $maximumRate);
        $manager->register(setting: $this->getMaximumRateSetting(rate: 10));

        $setting = $manager->get(key: $maximumRate->name());

        $this->assertSame($maximumRate->value(), $setting->value());
    }

    public function test_it_should_return_registered_setting_when_exists(): void
    {
        $manager = $this->createManager();
        $maximumRate = $this->getMaximumRateSetting();
        $manager->register(setting: $maximumRate);

        $setting = $manager->get(key: $maximumRate->name());

        $this->assertSame($maximumRate->value(), $setting->value());
    }

    public function test_it_can_register_setting(): void
    {
        $manager = $this->createManager();

        $manager->register(setting: $this->getMaximumRateSetting());

        $this->assertCount(1, $manager->getSettings());
    }

    // phpcs:ignore
    public function test_when_default_value_is_present_it_should_be_returned_instead_of_throwing_an_exception(): void
    {
        $manager = $this->createManager();

        $setting = $manager->get(key: 'empty.setting', default: $default = 80);

        $this->assertSame($setting->value(), (string) $default);
    }

    // phpcs:ignore
    public function test_proper_exception_needs_to_thrown_when_not_having_the_setting(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(MissingSettingException::class);

        $manager = $this->createManager();
        $maximumRate = $this->getMaximumRateSetting();

        $manager->get(key: $maximumRate->name());
    }

    public function test_it_should_return_the_setting_based_on_given_key(): void
    {
        $manager = $this->createManager();
        $maximumRate = $this->getMaximumRateSetting();
        $manager->set(setting: $maximumRate);

        $setting = $manager->get(key: $maximumRate->name());

        $this->assertInstanceOf(SettingInterface::class, $setting);
        $this->assertSame($maximumRate->name(), $setting->name());
        $this->assertSame($maximumRate->value(), $setting->value());
    }

    public function test_it_should_store_settings_in_target_storage(): void
    {
        $manager = $this->createManager();

        $manager->set($setting = $this->getMaximumRateSetting());

        $this->assertDatabaseHas(Setting::TABLE, [
            Setting::KEY => $setting->name(),
            Setting::VALUE => $setting->value(),
        ]);
    }

    private function getMaximumRateSetting(int $rate = 5): SettingInterface
    {
        return new class ($rate) implements SettingInterface {
            public function __construct(private readonly int $rate) {}

            public function name(): string
            {
                return 'maximum.rate';
            }

            public function value(): string
            {
                return $this->rate;
            }

            public function __toString(): string
            {
                return $this->name();
            }

            public function default(): string
            {
                return 5;
            }
        };
    }

    private function createManager(): SettingManager
    {
        return new SettingManager(new SettingContainer());
    }
}
