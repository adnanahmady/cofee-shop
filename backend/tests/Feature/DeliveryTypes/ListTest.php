<?php

namespace Tests\Feature\DeliveryTypes;

use App\Http\Resources\Api\V1\OrderTypes\List\DataCollection;
use App\Http\Resources\Api\V1\Shared\DeliveryTypeResource;
use App\Models\DeliveryType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\LetsBeTrait;

class ListTest extends TestCase
{
    use RefreshDatabase;
    use LetsBeTrait;

    public function test_data_item_should_contain_expected_fields(): void
    {
        $this->withoutExceptionHandling();
        createDeliveryType(count: 3);
        $this->login();

        $item = $this->request()->json(
            join('.', [DataCollection::DATA, 1])
        );

        $this->assertIsInt($item[DeliveryTypeResource::ID]);
        $this->assertIsString($item[DeliveryTypeResource::NAME]);
        $this->assertArrayNotHasKeys([
            DeliveryType::UPDATED_AT,
            DeliveryType::CREATED_AT,
        ], $item);
    }

    public function test_it_should_return_the_list_of_available_order_types(): void
    {
        $this->withoutExceptionHandling();
        createDeliveryType(count: $count = 3);
        $this->login();

        $data = $this->request()->json(DataCollection::DATA);

        $this->assertCount($count, $data);
    }

    public function test_it_should_not_be_accessible_for_unauthorized_users(): void
    {
        $response = $this->request();

        $response->assertUnauthorized();
    }

    public function test_it_should_response_with_proper_status_code(): void
    {
        $this->withoutExceptionHandling();
        $this->login();

        $response = $this->request();

        $response->assertOk();
    }

    private function request(): TestResponse
    {
        $url = route('api.v1.delivery-types.index');

        return $this->getJson($url);
    }

    private function login(): User
    {
        return $this->letsBe(createUser());
    }
}
