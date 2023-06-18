<?php

namespace Tests\Feature\Products;

use App\Enums\AbilityEnum;
use App\Http\Requests\Api\V1\Products\StoreRequest;
use App\Http\Resources\Api\V1\Products\Store;
use App\Models\Ability;
use App\Models\User;
use App\ValueObjects\Products\PriceObject;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Traits\LetsBeTrait;
use Tests\Traits\MigrateDatabaseTrait;

class CreateTest extends TestCase
{
    use MigrateDatabaseTrait;
    use LetsBeTrait;

    public function test_only_managers_should_be_able_to_add_products(): void
    {
        $this->login(abilities: ['not-manager']);
        $data = [
            StoreRequest::NAME => 'radio',
            StoreRequest::PRICE => 345345,
            StoreRequest::CURRENCY => createCurrency()->getId(),
        ];

        $response = $this->request($data);

        $response->assertForbidden();
    }

    public function test_response_should_not_escape_unicode_characters(): void
    {
        $this->withoutExceptionHandling();
        $this->login();
        $data = [
            StoreRequest::NAME => $name = 'تلویزیون',
            StoreRequest::PRICE => 345345,
            StoreRequest::CURRENCY => createCurrency()->getId(),
        ];

        $content = $this->request($data)->getContent();

        $this->assertStringContainsString($name, $content);
    }

    public static function dataProviderForValidation(): array
    {
        return [
            'name should be at least 2 characters' => [[
                StoreRequest::NAME => 't',
                StoreRequest::PRICE => 345345,
                StoreRequest::CURRENCY => 1,
                'createCurrency' => true,
            ]],
            'price should be integer' => [[
                StoreRequest::NAME => 'tv',
                StoreRequest::PRICE => '435-3453',
                StoreRequest::CURRENCY => 1,
                'createCurrency' => true,
            ]],
            'currency should exist in system' => [[
                StoreRequest::NAME => 'tv',
                StoreRequest::PRICE => 993443,
                StoreRequest::CURRENCY => 1,
            ]],
            'currency should be integer' => [[
                StoreRequest::NAME => 'tv',
                StoreRequest::PRICE => 993443,
                StoreRequest::CURRENCY => 'some',
            ]],
            'currency is required' => [[
                StoreRequest::NAME => 'tv',
                StoreRequest::PRICE => 993443,
            ]],
            'price is required' => [[
                StoreRequest::NAME => 'tv',
                StoreRequest::CURRENCY => 1,
                'createCurrency' => true,
            ]],
            'name is required' => [[
                StoreRequest::PRICE => 993443,
                StoreRequest::CURRENCY => 1,
                'createCurrency' => true,
            ]],
        ];
    }

    #[DataProvider('dataProviderForValidation')]
    public function test_it_should_pass_validation(array $data): void
    {
        if (key_exists('createCurrency', $data)) {
            $data[StoreRequest::CURRENCY] = createCurrency()->getId();
        }
        $this->login();

        $response = $this->request($data);

        $response->assertUnprocessable();
        $this->assertCount(1, $response->json('errors'));
    }

    public function test_meta_should_be_in_expected_form(): void
    {
        $this->withoutExceptionHandling();
        $this->login();
        $priceObject = new PriceObject(100033, createCurrency()->getId());
        $data = [
            StoreRequest::NAME => 'clock',
            StoreRequest::PRICE => $priceObject->getPrice(),
            StoreRequest::CURRENCY => $priceObject->getCurrency()->getId(),
        ];

        $meta = $this->request($data)->json(
            Store\StoredResource::META
        );

        $this->assertArrayHasKey(Store\MetaResource::CURRENCY, $meta);
        $this->assertArrayHasKeys([
            Store\CurrencyResource::ID,
            Store\CurrencyResource::NAME,
            Store\CurrencyResource::CODE,
        ], $currency = $meta[Store\MetaResource::CURRENCY]);
        $this->assertIsInt($currency[Store\CurrencyResource::ID]);
        $this->assertIsString($currency[Store\CurrencyResource::NAME]);
        $this->assertIsString($currency[Store\CurrencyResource::CODE]);
    }

    public function test_data_should_be_in_expected_form(): void
    {
        $this->withoutExceptionHandling();
        $this->login();
        $priceObject = new PriceObject(100033, createCurrency()->getId());
        $data = [
            StoreRequest::NAME => 'clock',
            StoreRequest::PRICE => $priceObject->getPrice(),
            StoreRequest::CURRENCY => $priceObject->getCurrency()->getId(),
        ];

        $data = $this->request($data)->json(
            Store\StoredResource::DATA
        );

        $this->assertArrayHasKeys([
            Store\ProductResource::ID,
            Store\ProductResource::NAME,
            Store\ProductResource::PRICE,
        ], $data);
        $this->assertIsInt($data[Store\ProductResource::ID]);
        $this->assertIsString($data[Store\ProductResource::NAME]);
        $this->assertIsString($data[Store\ProductResource::PRICE]);
        $this->assertSame(
            $priceObject->getFullForm(),
            $data[Store\ProductResource::PRICE]
        );
    }

    public function test_it_responses_with_proper_status_code(): void
    {
        $this->login();
        $data = [
            StoreRequest::NAME => 'clock',
            StoreRequest::PRICE => 1000,
            StoreRequest::CURRENCY => createCurrency()->getId(),
        ];

        $response = $this->request($data);

        $response->assertCreated();
    }

    private function request(array $data): TestResponse
    {
        return $this->postJson(
            uri: route('api.v1.products.store'),
            data: $data
        );
    }

    private function login(User $user = null, array $abilities = []): void
    {
        if (null === $user) {
            $user = createUser();
        }
        if (empty($abilities)) {
            $user->roles()->save($role = createRole());
            $role->abilities()->save($ability = createAbility([
                Ability::SLUG => AbilityEnum::AddProduct->slugify(),
            ]));
            $abilities = [$ability->getSlug()];
        }
        $this->letsBe($user, $abilities);
    }
}
