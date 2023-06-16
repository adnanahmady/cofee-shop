<?php

namespace Tests\Feature\Auth;

use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Resources\Api\V1\Auth\Login;
use App\Models\User;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Traits\MigrateDatabaseTrait;

class LoginTest extends TestCase
{
    use MigrateDatabaseTrait;

    public function test_response_should_not_escape_unicode_characters(): void
    {
        $this->withoutExceptionHandling();
        $user = createUser(fields: [User::FIRST_NAME => 'اصفر']);

        $content = $this->request(
            email: $user->getEmail(),
            password: 'password',
        )->getContent();

        $this->assertStringContainsString(
            $user->getName()->getFirstName(),
            $content
        );
    }

    public static function dataProviderForValidation(): array
    {
        return [
            'credentials should be correct' => [[
                'email' => 'user@example.com',
                'password' => 'invalid-password',
                'createUser' => true,
            ]],
            'password is required' => [[
                'email' => 'user@example.com',
                'createUser' => true,
            ]],
            'email is required' => [[
                'password' => 'password',
            ]],
            'email need to exist in database' => [[
                'email' => 'user@example.com',
                'password' => 'password',
            ]],
            'email need to be in email format' => [[
                'email' => 'not.in.email.form',
                'password' => 'password',
            ]],
        ];
    }

    #[DataProvider('dataProviderForValidation')]
    public function test_it_should_pass_validation(array $data): void
    {
        key_exists('createUser', $data) ?
            createUser(fields: [
                LoginRequest::EMAIL => $data[LoginRequest::EMAIL],
            ]) :
            createUser();

        $response = $this->request(...$data);

        $response->assertUnprocessable();
        $this->assertCount(1, $response->json('errors'));
    }

    public function test_meta_should_be_in_expected_form(): void
    {
        $this->withoutExceptionHandling();
        $user = createUser();

        $meta = $this->request(
            email: $user->getEmail(),
            password: 'password',
        )->json(Login\LoggedInResource::META);

        $this->assertSame([
            Login\UserResource::ID => $user->getId(),
            Login\UserResource::NAME => $user->getName()->getFullName(),
        ], $meta[Login\MetaResource::USER]);
    }

    public function test_data_should_in_in_expected_form(): void
    {
        $this->withoutExceptionHandling();
        $user = createUser();

        $data = $this->request(
            email: $user->getEmail(),
            password: 'password',
        )->json(Login\LoggedInResource::DATA);

        $this->assertArrayHasKey(Login\DataResource::ACCESS_TOKEN, $data);
        $this->assertIsString($data[Login\DataResource::ACCESS_TOKEN]);
    }

    public function test_it_response_with_proper_status(): void
    {
        $user = createUser();

        $response = $this->request(
            email: $user->getEmail(),
            password: 'password',
        );

        $response->assertOk();
    }

    private function request(mixed ...$data): TestResponse
    {
        return $this->postJson(
            uri: route(name: 'api.v1.login'),
            data: $data
        );
    }
}
