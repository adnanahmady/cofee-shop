<?php

namespace Tests\Feature\Settings;

use App\Enums\AbilityEnum;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Resources\Api\V1\Settings\List;
use App\Interfaces\SettingInterface;
use App\Models\Setting;
use App\Settings\SettingManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use Tests\TestCase;
use Tests\Traits\LetsBeTrait;

#[CoversClass(SettingController::class)]
#[CoversFunction('index')]
class GetAllTest extends TestCase
{
    use RefreshDatabase;
    use LetsBeTrait;

    // phpcs:ignore
    public function test_unset_setting_is_showing_its_value_as_null_with_default(): void
    {
        $this->loginAsAdmin();
        $firstSetting = $this->getDummySetting(name: 'dummy.setting');
        $secondSetting = $this->getDummySetting(name: 'dummy.setting.2');
        createSetting([Setting::KEY => $firstSetting->name()]);
        $manager = resolve(SettingManager::class);
        $manager->register($firstSetting);
        $manager->register($secondSetting);
        $setting = $manager->get($secondSetting);

        $data = $this->request()->json(List\PaginatorResource::DATA);

        $this->assertSame([
            List\ItemResource::NAME => $setting->name(),
            List\ItemResource::VALUE => null,
            List\ItemResource::DEFAULT => $setting->default(),
        ], $data[count($data) - 1]);
    }

    // phpcs:ignore
    public function test_each_data_item_should_contain_expected_setting_information(): void
    {
        $this->loginAsAdmin();
        $dummySetting = $this->getDummySetting();
        createSetting([Setting::KEY => $dummySetting->name()]);
        $manager = resolve(SettingManager::class);
        $manager->register($dummySetting);
        $setting = $manager->get($dummySetting);

        $data = $this->request()->json(List\PaginatorResource::DATA);

        $this->assertSame([
            List\ItemResource::NAME => $setting->name(),
            List\ItemResource::VALUE => $setting->value(),
            List\ItemResource::DEFAULT => $setting->default(),
        ], $data[count($data) - 1]);
    }

    // phpcs:ignore
    public function test_it_should_return_the_list_of_settings_regardless_of_being_stored_in_system_or_not(): void
    {
        $this->loginAsAdmin();
        $manager = resolve(SettingManager::class);
        $manager->register($this->getDummySetting());

        $data = $this->request()->json(List\PaginatorResource::DATA);

        $registeredSettingsCount = count($manager->getSettings());
        $this->assertGreaterThanOrEqual(1, $registeredSettingsCount);
        $this->assertCount($registeredSettingsCount, $data);
    }

    private function getDummySetting(
        string $name = 'dummy.setting'
    ): SettingInterface {
        return new class ($name) implements SettingInterface {
            public function __construct(private readonly string $name) {}

            public function name(): string
            {
                return $this->name;
            }

            public function value(): string
            {
                return 5;
            }

            public function default(): string
            {
                return 10;
            }

            public function __toString(): string
            {
                return $this->name();
            }
        };
    }

    /** Admin referees to a user with proper abilities. */
    public function test_only_admin_can_access_the_service(): void
    {
        $this->login();

        $response = $this->request();

        $response->assertForbidden();
    }

    public function test_it_should_response_with_correct_status_code(): void
    {
        $this->loginAsAdmin();

        $response = $this->request();

        $response->assertOk();
    }

    private function request(): TestResponse
    {
        return $this->getJson(route('api.v1.settings.index'));
    }

    private function login(array $abilities = []): void
    {
        $this->letsBe(createUser(), abilities: $abilities);
    }

    private function loginAsAdmin(): void
    {
        $this->login(abilities: [AbilityEnum::GetAllSettings->slugify()]);
    }
}
