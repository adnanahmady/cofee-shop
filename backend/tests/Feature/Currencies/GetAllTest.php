<?php

namespace Tests\Feature\Currencies;

use App\Api\Exchanges;
use App\Http\Controllers\Api\V1\CurrencyController;
use App\Http\Resources\Api\V1\Currencies\List\ItemResource;
use App\Http\Resources\Api\V1\Currencies\List\PaginatorResource;
use App\Models\User;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use Tests\TestCase;
use Tests\Traits\LetsBeTrait;

/**
 * Since the Exchanges api models doesn't call any
 * external API service, the mockery didn't implement
 * on the test. but whenever that an external service
 * is called, you need to mock it.
 */
#[CoversClass(CurrencyController::class)]
#[CoversFunction('index')]
class GetAllTest extends TestCase
{
    use LetsBeTrait;

    // phpcs:ignore
    public function test_each_currency_item_should_contain_expected_fields(): void
    {
        $this->login();
        $currency = (new Exchanges())->getIndexedRates(0);

        $item = $this->request()->json(join('.', [
            PaginatorResource::DATA,
            0,
        ]));

        $this->assertSame([
            ItemResource::CODE => $currency->code(),
            ItemResource::RATE => $currency->rate(),
        ], $item);
    }

    // phpcs:ignore
    public function test_it_should_be_only_accessible_by_authorized_users(): void
    {
        $response = $this->request();

        $response->assertUnauthorized();
    }

    public function test_it_should_response_with_expected_status_code(): void
    {
        $this->login();

        $response = $this->request();

        $response->assertOk();
    }

    private function login(): User
    {
        return $this->letsBe(createUser());
    }

    private function request(): TestResponse
    {
        return $this->getJson(route('api.v1.currencies.index'));
    }
}
