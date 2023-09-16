<?php

namespace Tests\Feature\OrderTypes;

use App\Enums\AbilityEnum;
use App\Http\Controllers\Api\V1\Orders\TypeController;
use App\Http\Requests\Api\V1\OrderTypes\UpdateRequest;
use App\Interfaces\IdInterface;
use App\Models\DeliveryType;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use Tests\TestCase;
use Tests\Traits\LetsBeTrait;
use App\Http\Resources\Api\V1\Orders\{Updated, Shared};
use App\Http\Resources\Api\V1\Shared as GeneralShared;

#[CoversClass(TypeController::class)]
#[CoversFunction('update')]
class UpdateTest extends TestCase
{
    use RefreshDatabase;
    use LetsBeTrait;

    public function test_it_should_show_delivery_type_as_expected(): void
    {
        $user = $this->login();
        $order = createOrder(for: $user);
        $deliveryType = createDeliveryType();
        $data = $this->prepareData($deliveryType);

        $data = $this->request(data: $data, order: $order)->json(join('.', [
            Updated\PaginatorResource::DATA,
            Shared\DataResource::DELIVERY_TYPE,
        ]));

        $this->assertIsInt($data[GeneralShared\DeliveryTypeResource::ID]);
        $this->assertIsString($data[GeneralShared\DeliveryTypeResource::NAME]);
    }

    public function test_it_should_response_with_proper_data(): void
    {
        $user = $this->login();
        $order = createOrder(for: $user);
        $deliveryType = createDeliveryType();
        createOrderItem([OrderItem::ORDER => $order]);
        $data = $this->prepareData($deliveryType);

        $data = $this->request(data: $data, order: $order)->json(
            Updated\PaginatorResource::DATA
        );

        $this->assertIsInt($data[Shared\DataResource::ORDER_ID]);
        $this->assertIsArray($data[Shared\DataResource::ITEMS]);
        $this->assertIsString($data[Shared\DataResource::TOTAL_PRICE]);
        $this->assertIsArray($data[Shared\DataResource::STATUS]);
        $this->assertIsArray($data[Shared\DataResource::DELIVERY_TYPE]);
    }

    public function test_a_manager_is_able_to_update_another_users_order(): void
    {
        $this->login([AbilityEnum::SetOrderType->slugify()]);
        $deliveryType = createDeliveryType();
        $data = $this->prepareData($deliveryType);

        $response = $this->request(data: $data);

        $response->assertOk();
    }

    public function test_a_customer_can_only_update_its_own_order(): void
    {
        $this->login();
        $deliveryType = createDeliveryType();
        $data = $this->prepareData($deliveryType);

        $response = $this->request(data: $data);

        $response->assertForbidden();
    }

    public function test_it_should_update_order_type_as_demand(): void
    {
        $deliveryType = createDeliveryType();
        $user = $this->login();
        $order = createOrder([Order::USER => $user]);
        $this->assertDatabaseMissing(
            Order::TABLE,
            $this->prepareTableData($order, $deliveryType)
        );
        $data = $this->prepareData($deliveryType);

        $this->request(data: $data, order: $order);

        $this->assertDatabaseHas(
            Order::TABLE,
            $this->prepareTableData($order, $deliveryType)
        );
    }

    public function prepareTableData(
        Order $order,
        DeliveryType $deliveryType
    ): array {
        return [
            Order::ID => $order->getId(),
            Order::DELIVERY_TYPE => $deliveryType->getId(),
        ];
    }

    public function test_it_should_response_with_proper_status_code(): void
    {
        $user = $this->login();
        $order = createOrder([Order::USER => $user]);
        $deliveryType = createDeliveryType();
        $data = $this->prepareData($deliveryType);

        $response = $this->request(data: $data, order: $order);

        $response->assertOk();
    }

    private function request(array $data, mixed $order = null): TestResponse
    {
        $url = route('api.v1.order-types.update', [
            'order' => $order ?? createOrder(),
        ]);

        return $this->patchJson(uri: $url, data: $data);
    }

    private function login(array $abilities = []): User
    {
        return $this->letsBe(createUser(), abilities: $abilities);
    }

    private function prepareData(IdInterface $deliveryType): array
    {
        return [UpdateRequest::DELIVERY_TYPE => $deliveryType->getId()];
    }
}
