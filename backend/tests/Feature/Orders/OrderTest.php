<?php

namespace Tests\Feature\Orders;

use App\Http\Requests\Api\V1\Orders\StoreRequest;
use App\Http\Resources\Api\V1\Orders\Stored;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\User;
use App\Support\OrderStateDeterminer\Values\WaitingValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Traits\LetsBeTrait;
use App\Http\Resources\Api\V1\Orders\Shared;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    use LetsBeTrait;

    public function test_the_order_type_needs_to_get_set(): void
    {
        $this->withoutExceptionHandling();
        $this->login();
        $maker = new OrderMaker();
        $maker->createItem();
        $maker->createItem(orderedAmount: 3);
        $deliveryType = createDeliveryType();
        $maker->setDeliveryType($deliveryType->getId());
        $data = $maker->make();

        $order = $this->request($data)->json(join('.', [
            Stored\PaginatorResource::DATA,
        ]));

        $this->assertSame([
            Shared\DeliveryTypeResource::ID => $deliveryType->getId(),
            Shared\DeliveryTypeResource::NAME => $deliveryType->getName(),
        ], $order[Shared\DataResource::DELIVERY_TYPE]);
    }

    public function test_data_item_should_be_in_expected_form(): void
    {
        $this->withoutExceptionHandling();
        $data = $this->getData();

        $items = $this->request($data)->json(join('.', [
            Stored\PaginatorResource::DATA,
            Shared\DataResource::ITEMS,
        ]));

        $this->assertCount(3, $items);
        $this->assertArrayHasKeys([
            Shared\ItemResource::ITEM_ID,
            Shared\ItemResource::NAME,
            Shared\ItemResource::AMOUNT,
            Shared\ItemResource::UNIT_PRICE,
            Shared\ItemResource::PRICE,
        ], $items[1]);
    }

    public function test_status_should_only_show_the_expected_fields(): void
    {
        $status = $this->getData();

        $status = $this->request($status)->json(join('.', [
            Stored\PaginatorResource::DATA,
            Shared\DataResource::STATUS,
        ]));

        $this->assertArrayHasKeys([
            Shared\StatusResource::ID,
            Shared\StatusResource::NAME,
        ], $status);
        $this->assertSame(
            (string) new WaitingValue(),
            $status[Shared\StatusResource::NAME]
        );
        $this->assertArrayNotHasKeys([
            OrderStatus::CREATED_AT,
            OrderStatus::UPDATED_AT,
        ], $status);
    }

    public function test_response_should_be_in_expected_form(): void
    {
        $data = $this->getData();

        $data = $this->request($data)->json(
            Stored\PaginatorResource::DATA
        );

        $this->assertArrayHasKeys([
            Shared\DataResource::ORDER_ID,
            Shared\DataResource::ITEMS,
            Shared\DataResource::TOTAL_PRICE,
            Shared\DataResource::STATUS,
        ], $data);
    }

    private function getData(): array
    {
        $this->login();
        $maker = new OrderMaker();
        $maker->createItem();
        $maker->createItem(orderedAmount: 3);
        $maker->createItem(orderedAmount: 2);

        return $maker->make();
    }

    public static function dataProviderForValidationTest(): array
    {
        return [
            'delivery type needs to exist in system' => [[
                StoreRequest::PRODUCTS => [
                    [
                        StoreRequest::PRODUCT_ID => 1,
                        StoreRequest::AMOUNT => 1,
                    ],
                ],
                StoreRequest::DELIVERY_TYPE => 1,
                'makeProduct' => true,
            ]],
            'delivery type needs to be integer' => [[
                StoreRequest::PRODUCTS => [
                    [
                        StoreRequest::PRODUCT_ID => 1,
                        StoreRequest::AMOUNT => 1,
                    ],
                ],
                StoreRequest::DELIVERY_TYPE => 'a',
                'makeProduct' => true,
            ]],
            'delivery type is required' => [[
                StoreRequest::PRODUCTS => [
                    [
                        StoreRequest::PRODUCT_ID => 1,
                        StoreRequest::AMOUNT => 1,
                    ],
                ],
                'makeProduct' => true,
            ]],
            'requested item amount should be less than the product amount' => [[
                StoreRequest::PRODUCTS => [
                    [
                        StoreRequest::PRODUCT_ID => 1,
                        StoreRequest::AMOUNT => 10,
                    ],
                ],
                'makeProduct' => true,
                'makeDeliveryType' => true,
            ]],
            'amount should be integer' => [[
                StoreRequest::PRODUCTS => [
                    [
                        StoreRequest::PRODUCT_ID => 1,
                        StoreRequest::AMOUNT => 'a',
                    ],
                ],
                'makeProduct' => true,
                'makeDeliveryType' => true,
            ]],
            'product should exist in system' => [[
                StoreRequest::PRODUCTS => [
                    [StoreRequest::PRODUCT_ID => 1, StoreRequest::AMOUNT => 10],
                ],
                'makeDeliveryType' => true,
            ]],
            'product is required' => [[
                StoreRequest::PRODUCTS => [
                    [StoreRequest::AMOUNT => 10],
                ],
                'makeDeliveryType' => true,
            ]],
            'products should contain at least one item' => [[
                StoreRequest::PRODUCTS => [],
                'makeDeliveryType' => true,
            ]],
            'products is required' => [[
                'makeDeliveryType' => true,
            ]],
        ];
    }

    #[DataProvider('dataProviderForValidationTest')]
    public function test_data_validation(array $data): void
    {
        $this->login();
        $newPId = fn () => createProduct([Product::AMOUNT => 5])->getId();

        if (key_exists('makeProduct', $data)) {
            $data[StoreRequest::PRODUCTS] = array_map(
                function ($i) use ($newPId) {
                    $i[StoreRequest::PRODUCT_ID] = $newPId();

                    return $i;
                },
                $data[StoreRequest::PRODUCTS]
            );
        }

        if (key_exists('makeDeliveryType', $data)) {
            $data[StoreRequest::DELIVERY_TYPE] = createDeliveryType()->getId();
        }

        $response = $this->request($data);

        $response->assertUnprocessable();
        $this->assertCount(1, $response->json('errors'));
    }

    public function test_when_ordered_items_are_more_than_product_amount_user_can_not_purchase(): void
    {
        $this->login();
        $maker = new OrderMaker();
        $p1 = $maker->createItem(orderedAmount: 6, productAmount: 5)->getId();
        $data = $maker->make();

        $response = $this->request($data);

        $response->assertUnprocessable();
        $this->assertDatabaseCount(Order::TABLE, 0);
        $this->assertDatabaseCount(OrderItem::TABLE, 0);
        $this->assertDatabaseHas(Product::TABLE, [
            Product::ID => $p1,
            Product::AMOUNT => 5,
        ]);
    }

    public function test_after_ordering_product_the_amount_of_product_should_be_reserved(): void
    {
        $this->withoutExceptionHandling();
        $this->login();
        $maker = new OrderMaker();
        $p1 = $maker->createItem(
            orderedAmount: $amount = 3,
            productAmount: 10
        )->getId();
        $data = $maker->make();

        $data = $this->request($data)->json(Stored\PaginatorResource::DATA);

        $orderId = $data[Shared\DataResource::ORDER_ID];
        $this->assertOrderItemHas([
            [$this->getItemId($data, 0), $orderId, $p1, $amount],
        ]);
        $this->assertDatabaseHas(Product::TABLE, [
            Product::ID => $p1,
            Product::AMOUNT => 7,
        ]);
    }

    public function test_user_should_be_able_to_order_multiple_items_of_the_same_product(): void
    {
        $this->withoutExceptionHandling();
        $this->login();
        $maker = new OrderMaker();
        $newItemId = fn (int $amount = 1) => $maker->createItem(
            orderedAmount: $amount,
            productAmount: 10
        )->getId();
        $p1 = $newItemId($amount = 3);
        $p2 = $newItemId();
        $p3 = $newItemId();
        $data = $maker->make();

        $data = $this->request($data)->json(Stored\PaginatorResource::DATA);

        $orderId = $data[Shared\DataResource::ORDER_ID];
        $this->assertOrderItemHas([
            [$this->getItemId($data, 0), $orderId, $p1, $amount],
            [$this->getItemId($data, 1), $orderId, $p2, 1],
            [$this->getItemId($data, 2), $orderId, $p3, 1],
        ]);
    }

    public function test_it_should_store_the_user_order(): void
    {
        $this->withoutExceptionHandling();
        $user = $this->login();
        $maker = new OrderMaker();
        $addItem = fn () => $maker->createItem(productAmount: 10)->getId();
        $p1 = $addItem();
        $p2 = $addItem();
        $p3 = $addItem();
        $data = $maker->make();

        $data = $this->request($data)->json(Stored\PaginatorResource::DATA);

        $orderId = $data[Shared\DataResource::ORDER_ID];
        $this->assertDatabaseHas(Order::TABLE, [
            Order::ID => $orderId,
            Order::USER => $user->getId(),
        ]);
        $this->assertOrderItemHas([
            [$this->getItemId($data, 0), $orderId, $p1],
            [$this->getItemId($data, 1), $orderId, $p2],
            [$this->getItemId($data, 2), $orderId, $p3],
        ]);
    }

    public function test_it_should_response_correctly(): void
    {
        $this->login();
        $maker = new OrderMaker();
        $maker->createItem();
        $data = $maker->make();

        $response = $this->request($data);

        $response->assertCreated();
    }

    private function getItemId(array $data, int $index): int
    {
        $itemsKey = Shared\DataResource::ITEMS;
        $itemIdKey = Shared\ItemResource::ITEM_ID;

        return $data[$itemsKey][$index][$itemIdKey];
    }

    private function assertOrderItemHas(array $orderItems): void
    {
        array_map(
            fn ($data) => $this->assertDatabaseHas(OrderItem::TABLE, [
                OrderItem::ID => $data[0],
                OrderItem::ORDER => $data[1],
                OrderItem::PRODUCT => $data[2],
                OrderItem::AMOUNT => $data[3] ?? 1,
            ]),
            $orderItems
        );
    }

    private function request(array $data): TestResponse
    {
        return $this->postJson(
            route('api.v1.orders.store'),
            data: $data
        );
    }

    private function login(): User
    {
        return $this->letsBe(createUser());
    }
}
