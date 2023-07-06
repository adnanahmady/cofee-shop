<?php

namespace Tests\Feature\Orders;

use App\Http\Resources\Api\V1\Orders\List;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\LetsBeTrait;

class GetListTest extends TestCase
{
    use RefreshDatabase;
    use LetsBeTrait;

    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository();
    }

    public function test_order_item_should_have_expected_fields(): void
    {
        $this->withoutExceptionHandling();
        [$orderItems] = $this->setItemsOfOrders();

        $item = $this->request()->json(join('.', [
            List\PaginatorResource::DATA,
            0,
            List\OrderResource::ITEMS,
            0,
        ]));

        $this->assertArrayHasKeys([
            List\OrderItemResource::ID,
            List\OrderItemResource::NAME,
            List\OrderItemResource::AMOUNT,
            List\OrderItemResource::PRICE,
        ], $item);
        $priceObject = $orderItems[0][0]->getPriceObject();
        $this->assertSame($priceObject->represent(), $item['price']);
    }

    public function test_the_status_field_should_determine_the_orders_status(): void
    {
        $this->withoutExceptionHandling();
        [, $orders] = $this->setItemsOfOrders();

        $status = $this->request()->json(join('.', [
            List\PaginatorResource::DATA,
            0,
            List\OrderResource::STATUS,
        ]));

        $this->assertArrayHasKeys([
            List\StatusResource::ID,
            List\StatusResource::NAME,
        ], $status);
        $this->assertArrayNotHasKeys([
            OrderStatus::CREATED_AT,
            OrderStatus::UPDATED_AT,
        ], $status);
        $this->assertSame(
            $orders[0]->status->getName(),
            $status[List\StatusResource::NAME]
        );
    }

    public function test_data_should_be_in_expected_form(): void
    {
        $this->setItemsOfOrders();

        $data = $this->request()->json(join('.', [
            List\PaginatorResource::DATA,
            0,
        ]));

        $this->assertArrayHasKeys([
            List\OrderResource::ID,
            List\OrderResource::ITEMS,
            List\OrderResource::TOTAL_PRICE,
            List\OrderResource::STATUS,
            List\OrderResource::ORDERED_AT,
            List\OrderResource::UPDATED_AT,
        ], $data);
    }

    private function setItemsOfOrders(): array
    {
        $orders = createOrder(count: 2);
        $orderItems = $orders->map(fn ($order) => createOrderItem(fields: [
            OrderItem::ORDER => $order,
        ], count: 3));
        $this->userRepository->saveOrders($this->login(), $orders);

        return [$orderItems, $orders];
    }

    public function test_data_should_contain_the_users_orders(): void
    {
        $this->withoutExceptionHandling();
        createOrder(count: 3);
        $this->userRepository->saveOrders(
            $this->login(),
            $userOrders = createOrder(count: 2)
        );

        $data = $this->request()->json(List\PaginatorResource::DATA);

        $this->assertCount($userOrders->count(), $data);
    }

    public function test_it_responses_correctly(): void
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
        return $this->getJson(route('api.v1.orders.index'));
    }
}
