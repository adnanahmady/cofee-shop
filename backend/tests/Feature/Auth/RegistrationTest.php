<?php

namespace Tests\Feature\Auth;

use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\Auth\Register;
use App\ValueObjects\Users\NameObject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_response_should_not_escape_unicode_characters(): void
    {
        $this->withoutExceptionHandling();
        $name = new NameObject('اصفر', 'مرادی');

        $content = $this->request(
            first_name: $name->getFirstName(),
            last_name: $name->getLastName(),
            email: 'user@example.com',
            password: 'password',
        )->getContent();

        $this->assertStringContainsString($name->getFullName(), $content);
    }

    public static function dataProviderForValidation(): array
    {
        return [
            'last_name is required' => [[
                RegisterRequest::FIRST_NAME => 'john',
                RegisterRequest::EMAIL => 'user@example.com',
                RegisterRequest::PASSWORD => 'password',
            ]],
            'first_name is required' => [[
                RegisterRequest::LAST_NAME => 'due',
                RegisterRequest::EMAIL => 'user@example.com',
                RegisterRequest::PASSWORD => 'password',
            ]],
            'password is required' => [[
                RegisterRequest::FIRST_NAME => 'john',
                RegisterRequest::LAST_NAME => 'due',
                RegisterRequest::EMAIL => 'user@example.com',
            ]],
            'email is required' => [[
                RegisterRequest::FIRST_NAME => 'john',
                RegisterRequest::LAST_NAME => 'due',
                RegisterRequest::PASSWORD => 'password',
            ]],
            'email need to be unique' => [[
                RegisterRequest::FIRST_NAME => 'john',
                RegisterRequest::LAST_NAME => 'due',
                RegisterRequest::EMAIL => 'user@example.com',
                'createUser' => true,
                RegisterRequest::PASSWORD => 'password',
            ]],
            'email need to be in email format' => [[
                RegisterRequest::FIRST_NAME => 'john',
                RegisterRequest::LAST_NAME => 'due',
                RegisterRequest::EMAIL => 'not.in.email.form',
                RegisterRequest::PASSWORD => 'password',
            ]],
        ];
    }

    #[DataProvider('dataProviderForValidation')]
    public function test_it_should_pass_validation(array $data): void
    {
        key_exists('createUser', $data) ?
            createUser(fields: [
                RegisterRequest::EMAIL => $data[RegisterRequest::EMAIL],
            ]) :
            createUser();

        $response = $this->request(...$data);

        $response->assertUnprocessable();
        $this->assertCount(1, $response->json('errors'));
    }

    public function test_data_should_be_in_expected_form(): void
    {
        $this->withoutExceptionHandling();
        $name = new NameObject('john', 'due');

        $data = $this->request(
            first_name: $name->getFirstName(),
            last_name: $name->getLastName(),
            email: 'user@example.com',
            password: 'password',
        )->json(Register\RegisteredResource::DATA);

        $this->assertArrayHasKeys([
            Register\UserResource::ID,
            Register\UserResource::NAME,
            Register\UserResource::EMAIL,
        ], $data);
        $this->assertIsInt($data[Register\UserResource::ID]);
        $this->assertIsString($data[Register\UserResource::EMAIL]);
        $this->assertSame(
            $name->getFullName(),
            $data[Register\UserResource::NAME]
        );
    }

    public function test_it_response_with_proper_status(): void
    {
        $response = $this->request(
            first_name: 'john',
            last_name: 'due',
            email: 'user@example.com',
            password: 'password',
        );

        $response->assertCreated();
    }

    private function request(mixed ...$data): TestResponse
    {
        return $this->postJson(
            uri: route(name: 'api.v1.register'),
            data: $data
        );
    }
}
