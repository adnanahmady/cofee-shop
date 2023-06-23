<?php

namespace Tests\Feature\Orders;

use App\Http\Requests\Api\V1\Orders\StoreRequest;
use App\Http\Resources\Api\V1\Orders\Store;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Traits\LetsBeTrait;
use Tests\Traits\MigrateDatabaseTrait;

class OrderTest extends TestCase
{
    use MigrateDatabaseTrait;
    use LetsBeTrait;

    public function test_data_item_should_be_in_expected_form(): void
    {
        $this->login();
        $newPId = fn () => createProduct([Product::AMOUNT => 10])->getId();
        $item = fn ($id, $amount = 1) => [
            StoreRequest::PRODUCT_ID => $id,
            StoreRequest::AMOUNT => $amount,
        ];
        $data = [StoreRequest::PRODUCTS => [
            $item($newPId()),
            $item($newPId(), 3),
            $item($newPId(), 2),
        ]];

        $items = $this->request($data)->json(join('.', [
            Store\PaginatorResource::DATA,
            Store\DataResource::ITEMS,
        ]));

        $this->assertCount(3, $items);
        $this->assertArrayHasKeys([
            Store\ItemResource::ITEM_ID,
            Store\ItemResource::NAME,
            Store\ItemResource::AMOUNT,
            Store\ItemResource::UNIT_PRICE,
            Store\ItemResource::PRICE,
        ], $items[1]);
    }

    public function test_response_should_be_in_expected_form(): void
    {
        $this->login();
        $newPId = fn () => createProduct([Product::AMOUNT => 10])->getId();
        $item = fn ($id, $amount = 1) => [
            StoreRequest::PRODUCT_ID => $id,
            StoreRequest::AMOUNT => $amount,
        ];
        $data = [StoreRequest::PRODUCTS => [
            $item($newPId()),
            $item($newPId(), 3),
            $item($newPId(), 2),
        ]];

        $data = $this->request($data)->json(
            Store\PaginatorResource::DATA
        );

        $this->assertArrayHasKeys([
            Store\DataResource::ORDER_ID,
            Store\DataResource::ITEMS,
            Store\DataResource::TOTAL_PRICE,
        ], $data);
    }

    public static function dataProviderForValidationTest(): array
    {
        return [
            'requested item amount should be less than the product amount' => [[
                StoreRequest::PRODUCTS => [
                    [
                        StoreRequest::PRODUCT_ID => 1,
                        StoreRequest::AMOUNT => 10,
                    ],
                ],
                'makeProduct' => true,
            ]],
            'amount should be integer' => [[
                StoreRequest::PRODUCTS => [
                    [
                        StoreRequest::PRODUCT_ID => 1,
                        StoreRequest::AMOUNT => 'a',
                    ],
                ],
                'makeProduct' => true,
            ]],
            'product should exist in system' => [[
                StoreRequest::PRODUCTS => [
                    [StoreRequest::PRODUCT_ID => 1, StoreRequest::AMOUNT => 10],
                ],
            ]],
            'product is required' => [[
                StoreRequest::PRODUCTS => [
                    [StoreRequest::AMOUNT => 10],
                ],
            ]],
            'products should contain at least one item' => [[
                StoreRequest::PRODUCTS => [],
            ]],
            'products is required' => [[]],
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

        $response = $this->request($data);

        $response->assertUnprocessable();
        $this->assertCount(1, $response->json('errors'));
    }

    public function test_when_ordered_items_are_more_than_product_amount_you_should_not_be_able_to_purchase(): void
    {
        $this->login();
        $newPId = fn () => createProduct([Product::AMOUNT => 5])->getId();
        $data = [StoreRequest::PRODUCTS => [[
            StoreRequest::PRODUCT_ID => $p1 = $newPId(),
            StoreRequest::AMOUNT => 6,
        ]]];

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
        $newPId = fn () => createProduct([Product::AMOUNT => 10])->getId();
        $data = [StoreRequest::PRODUCTS => [
            [
                StoreRequest::PRODUCT_ID => $p1 = $newPId(),
                StoreRequest::AMOUNT => $amount = 3,
            ],
        ]];

        $orderId = $this->request($data)->json(join('.', [
            Store\PaginatorResource::DATA,
            Store\DataResource::ORDER_ID,
        ]));

        $this->assertOrderItemHas([[1, $orderId, $p1, $amount]]);
        $this->assertDatabaseHas(Product::TABLE, [
            Product::ID => $p1,
            Product::AMOUNT => 7,
        ]);
    }

    public function test_user_should_be_able_to_order_multiple_items_of_the_same_product(): void
    {
        $this->withoutExceptionHandling();
        $this->login();
        $newPId = fn () => createProduct([Product::AMOUNT => 10])->getId();
        $data = [StoreRequest::PRODUCTS => [
            [
                StoreRequest::PRODUCT_ID => $p1 = $newPId(),
                StoreRequest::AMOUNT => $amount = 3,
            ],
            [StoreRequest::PRODUCT_ID => $p2 = $newPId()],
            [StoreRequest::PRODUCT_ID => $p3 = $newPId()],
        ]];

        $orderId = $this->request($data)->json(join('.', [
            Store\PaginatorResource::DATA,
            Store\DataResource::ORDER_ID,
        ]));

        $this->assertOrderItemHas([
            [1, $orderId, $p1, $amount],
            [2, $orderId, $p2, 1],
            [3, $orderId, $p3, 1],
        ]);
    }

    public function test_it_should_store_the_user_order(): void
    {
        $this->withoutExceptionHandling();
        $user = $this->login();
        $newPId = fn () => createProduct([Product::AMOUNT => 10])->getId();
        $data = [StoreRequest::PRODUCTS => [
            [StoreRequest::PRODUCT_ID => $p1 = $newPId()],
            [StoreRequest::PRODUCT_ID => $p2 = $newPId()],
            [StoreRequest::PRODUCT_ID => $p3 = $newPId()],
        ]];

        $orderId = $this->request($data)->json(join('.', [
            Store\PaginatorResource::DATA,
            Store\DataResource::ORDER_ID,
        ]));

        $this->assertDatabaseHas(Order::TABLE, [
            Order::ID => 1,
            Order::USER => $user->getId(),
        ]);
        $this->assertOrderItemHas([
            [1, $orderId, $p1],
            [2, $orderId, $p2],
            [3, $orderId, $p3],
        ]);
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

    public function test_it_should_response_correctly(): void
    {
        $this->login();
        $newPId = fn () => createProduct([Product::AMOUNT => 10])->getId();
        $data = [StoreRequest::PRODUCTS => [[
            StoreRequest::PRODUCT_ID => $newPId(),
        ]]];

        $response = $this->request($data);

        $response->assertCreated();
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
