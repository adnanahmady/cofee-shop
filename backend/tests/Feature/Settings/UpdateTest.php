<?php

namespace Tests\Feature\Settings;

use App\Enums\AbilityEnum;
use App\Http\Controllers\Api\V1\SettingController;
use App\Http\Resources\Api\V1\Settings\List;
use App\Interfaces\SettingInterface;
use App\Models\Setting;
use App\Models\User;
use App\Settings\SettingManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use Tests\TestCase;
use Tests\Traits\LetsBeTrait;

#[CoversClass(SettingController::class)]
#[CoversFunction('update')]
class UpdateTest extends TestCase
{
    use LetsBeTrait;
    use RefreshDatabase;

    // phpcs:ignore
    public function test_it_should_return_updated_settings_with_new_value(): void
    {
        $this->loginAsAdmin();
        $manager = resolve(SettingManager::class);
        $manager->register(
            $setting = $this->createSetting(name: 'dummy.setting')
        );
        $trash = $this->createSetting(name: 'dummy.setting.1');

        $data = $this->request(data: [
            $setting->name() => $value = 3,
            $trash->name() => 33,
        ])->json(List\PaginatorResource::DATA);

        $this->assertSame([[
            List\ItemResource::NAME => $setting->name(),
            List\ItemResource::VALUE => (string) $value,
            List\ItemResource::DEFAULT => $setting->default(),
        ]], $data);
    }

    // phpcs:ignore
    public function test_it_should_only_allow_registered_settings_to_get_set(): void
    {
        $this->loginAsAdmin();
        $setting = $this->createSetting(name: 'dummy.setting');

        $this->request(data: [$setting->name() => 10]);

        $this->assertDatabaseMissing(Setting::TABLE, [
            Setting::KEY => $setting->name(),
        ]);
    }

    public function test_it_should_update_specified_setting(): void
    {
        $this->loginAsAdmin();
        $manager = resolve(SettingManager::class);
        $manager->register(
            $setting = $this->createSetting(name: 'dummy.setting')
        );

        $this->request(data: [$setting->name() => $value = 10]);

        $this->assertDatabaseHas(Setting::TABLE, [
            Setting::KEY => $setting->name(),
            Setting::VALUE => $value,
        ]);
    }

    private function createSetting(string $name): SettingInterface
    {
        return new class ($name) implements SettingInterface {
            public function __construct(private readonly string $name) {}

            public function name(): string
            {
                return $this->name;
            }

            public function value(): null|string
            {
                return 5;
            }

            public function default(): null|string
            {
                return 10;
            }

            public function __toString(): string
            {
                return $this->name();
            }
        };
    }

    public function test_only_admin_can_set_a_setting(): void
    {
        $this->login();

        $response = $this->request();

        $response->assertForbidden();
    }

    // phpcs:ignore
    public function test_its_should_be_only_accessible_for_authenticated_users(): void
    {
        $response = $this->request();

        $response->assertUnauthorized();
    }

    public function test_it_should_response_with_expected_status_code(): void
    {
        $this->loginAsAdmin();

        $response = $this->request();

        $response->assertOk();
    }

    private function login(array $abilities = []): User
    {
        return $this->letsBe(createUser(), abilities: $abilities);
    }

    private function request(array $data = []): TestResponse
    {
        return $this->patchJson(route('api.v1.settings.update', $data));
    }

    private function loginAsAdmin(): User
    {
        return $this->login(abilities: [AbilityEnum::SetSettings->slugify()]);
    }
}
