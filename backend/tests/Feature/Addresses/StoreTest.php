<?php

namespace Tests\Feature\Addresses;

use App\Enums\AbilityEnum;
use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Requests\Api\V1\Address\StoreRequest;
use App\Http\Resources\Api\V1\Addresses\Stored;
use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Traits\LetsBeTrait;

#[CoversClass(AddressController::class)]
#[CoversFunction('store')]
class StoreTest extends TestCase
{
    use LetsBeTrait;
    use RefreshDatabase;

    public static function dataProviderForValidation(): array
    {
        return [
            'specified user identifier should be integer' => [[
                StoreRequest::USER_ID => 'a',
            ]],
            'specified user should exist in system' => [[
                StoreRequest::USER_ID => 10000,
            ]],
            'address description is should be 200 characters maximum' => [[
                StoreRequest::DESCRIPTION => fake()->realTextBetween(
                    minNbChars: 201,
                    maxNbChars: 220,
                ),
            ]],
            'address description is should be at least 10 characters' => [[
                StoreRequest::DESCRIPTION => 'some text',
            ]],
            'address postal code is required' => [[
                StoreRequest::POSTAL_CODE => '',
            ]],
            'address plate number is required' => [[
                StoreRequest::PLATE_NUMBER => '',
            ]],
            'street is required' => [[
                StoreRequest::STREET => '',
            ]],
            'city is required' => [[
                StoreRequest::CITY => '',
            ]],
            'address title is required' => [[
                StoreRequest::TITLE => '',
            ]],
        ];
    }

    #[DataProvider('dataProviderForValidation')]
    public function test_validation(array $manipulatedFields): void
    {
        $data = array_merge($this->prepareData(), $manipulatedFields);
        $this->letsBe(createUser());

        $response = $this->request(data: $data);

        $response->assertUnprocessable();
        $this->assertCount(1, $response->json('errors'));
    }

    public function test_manager_can_add_address_to_other_users(): void
    {
        $this->letsBe(
            createUser(),
            [AbilityEnum::AddAddressForOthers->slugify()]
        );
        $data = $this->prepareData() + [
            StoreRequest::USER_ID => $user = createUser(),
        ];

        $response = $this->request(data: $data);

        $response->assertCreated();
        $userId = $response->json(join('.', [
            Stored\PaginatorResource::META,
            Stored\MetaResource::USER,
            Stored\UserResource::ID,
        ]));
        $this->assertSame($user->getId(), $userId);
        $this->assertAddressIsStored($user, $data);
    }

    // phpcs:ignore
    public function test_non_manager_user_can_only_add_address_for_itself(): void
    {
        $this->letsBe(createUser());
        $data = $this->prepareData() + [
            StoreRequest::USER_ID => createUser(),
        ];

        $response = $this->request(data: $data);

        $response->assertForbidden();
    }

    // phpcs:ignore
    public function test_meta_section_should_contain_address_owner_information(): void
    {
        $this->withoutExceptionHandling();
        $this->letsBe(createUser());
        $data = $this->prepareData();

        $meta = $this->request(data: $data)->json(
            Stored\PaginatorResource::META
        );

        $this->assertArrayHasKey(Stored\MetaResource::USER, $meta);
        $this->assertArrayHasKeys([
            Stored\UserResource::ID,
            Stored\UserResource::NAME,
        ], $meta[Stored\MetaResource::USER]);
    }

    public function test_data_section_of_response_should_be_as_expected(): void
    {
        $this->letsBe(createUser());
        $data = $this->prepareData();

        $data = $this->request(data: $data)->json(
            Stored\PaginatorResource::DATA
        );

        $this->assertArrayHasKeys([
            Stored\DataResource::TITLE,
            Stored\DataResource::CITY,
            Stored\DataResource::STREET,
            Stored\DataResource::PLATE_NUMBER,
            Stored\DataResource::POSTAL_CODE,
            Stored\DataResource::DESCRIPTION,
        ], $data);
    }

    public function test_address_should_get_stored(): void
    {
        $this->letsBe($user = createUser());
        $data = $this->prepareData();

        $this->request(data: $data);

        $this->assertAddressIsStored($user, $data);
    }

    private function assertAddressIsStored(User $user, array $data): void
    {
        [
            StoreRequest::TITLE => $title,
            StoreRequest::CITY => $city,
            StoreRequest::STREET => $street,
            StoreRequest::PLATE_NUMBER => $plateNumber,
            StoreRequest::POSTAL_CODE => $postalCode,
            StoreRequest::DESCRIPTION => $description,
        ] = $data;
        $this->assertDatabaseHas(Address::TABLE, [
            Address::TITLE => $title,
            Address::USER => $user->getId(),
            Address::CITY => $city,
            Address::STREET => $street,
            Address::PLATE_NUMBER => $plateNumber,
            Address::POSTAL_CODE => $postalCode,
            Address::DESCRIPTION => $description,
        ]);
    }

    // phpcs:ignore
    public function test_it_should_be_available_only_for_authenticated_users(): void
    {
        $response = $this->request();

        $response->assertUnauthorized();
    }

    public function test_it_should_response_with_correct_status_code(): void
    {
        $this->withoutExceptionHandling();
        $this->letsBe(createUser());

        $response = $this->request();

        $response->assertCreated();
    }

    private function request(
        array $data = null
    ): TestResponse {
        return $this->postJson(route(
            'api.v1.addresses.store',
            $data ?? $this->prepareData()
        ));
    }

    private function prepareData(): array
    {
        return [
            StoreRequest::TITLE => fake()->title(),
            StoreRequest::CITY => fake()->city(),
            StoreRequest::STREET => fake()->streetName(),
            StoreRequest::PLATE_NUMBER => 'H-334A',
            StoreRequest::POSTAL_CODE => fake()->postcode(),
            StoreRequest::DESCRIPTION => fake()->text(),
        ];
    }
}
