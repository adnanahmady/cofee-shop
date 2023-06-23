<?php

namespace Tests\Feature\OrderItems;

use App\Http\Requests\Api\V1\OrderItems\UpdateRequest;
use App\Http\Resources\Api\V1\OrderItems;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Repositories\ProductRepository;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Traits\LetsBeTrait;
use Tests\Traits\MigrateDatabaseTrait;

class UpdateTest extends TestCase
{
    use MigrateDatabaseTrait;
    use LetsBeTrait;

    public function test_order_item_can_only_be_integer(): void
    {
        $this->login();

        $response = $this->request(data: [], orderItem: 'not-item');

        $response->assertNotFound();
    }

    public function test_invalid_order_item_should_face_not_found(): void
    {
        $this->login();

        $response = $this->request(data: [], orderItem: 1);

        $response->assertNotFound();
    }

    public static function dataProviderForDataValidationTest(): array
    {
        return [
            'amount should be integer' => [[
                UpdateRequest::AMOUNT => 'a',
            ]],
            'amount is required' => [[]],
        ];
    }

    #[DataProvider('dataProviderForDataValidationTest')]
    public function test_data_validation(array $data): void
    {
        $this->login();
        $orderItem = createOrderItem();

        $response = $this->request($data, $orderItem);

        $response->assertUnprocessable();
        $this->assertCount(1, $response->json('errors'));
    }

    public static function dataProviderForRemovalTest(): array
    {
        return [
            'less than zero amount means removal' => [[
                UpdateRequest::AMOUNT => -10,
            ]],
            'zero amount of a product means removal' => [[
                UpdateRequest::AMOUNT => 0,
            ]],
        ];
    }

    #[DataProvider('dataProviderForRemovalTest')]
    public function test_remove_order_item(array $data): void
    {
        $this->login();
        [$orderItem, $product] = $this->orderProduct(
            productAmount: $initialAmount = 10,
            orderAmount: 6,
        );

        $response = $this->request($data, $orderItem);

        $response->assertNoContent();
        $this->assertDatabaseMissing(OrderItem::TABLE, [
            OrderItem::ID => $orderItem->getId(),
        ]);
        $this->assertDatabaseHas(Product::TABLE, [
            Product::ID => $product->getId(),
            Product::AMOUNT => $initialAmount,
        ]);
    }

    public function test_it_should_release_user_unwanted_amount_of_products(): void
    {
        $this->login();
        [$orderItem, $product] = $this->orderProduct(
            productAmount: 10,
            orderAmount: 6,
        );
        $data = [UpdateRequest::AMOUNT => 4];

        $response = $this->request($data, $orderItem);

        $response->assertOk();
        $this->assertDatabaseHas(OrderItem::TABLE, [
            OrderItem::ID => $orderItem->getId(),
            OrderItem::AMOUNT => 4,
        ]);
        $this->assertDatabaseHas(Product::TABLE, [
            Product::ID => $product->getId(),
            Product::AMOUNT => 6,
        ]);
    }

    public function test_it_should_undo_invalid_amount_for_requested_products(): void
    {
        $this->login();
        [$orderItem, $product] = $this->orderProduct(
            productAmount: 3,
            orderAmount: 1
        );
        $data = [UpdateRequest::AMOUNT => 4];

        $response = $this->request($data, $orderItem);

        $response->assertUnprocessable();
        $this->assertDatabaseHas(OrderItem::TABLE, [
            OrderItem::ID => $orderItem->getId(),
            OrderItem::AMOUNT => 1,
        ]);
        $this->assertDatabaseHas(Product::TABLE, [
            Product::ID => $product->getId(),
            Product::AMOUNT => 2,
        ]);
    }

    private function orderProduct(
        int $productAmount,
        int $orderAmount
    ): array {
        $repo = new ProductRepository();
        $initialProduct = createProduct([Product::AMOUNT => $productAmount]);
        $product = $repo->orderProduct($initialProduct, $orderAmount);

        return [createOrderItem([
            OrderItem::AMOUNT => $orderAmount,
            OrderItem::PRODUCT => $product,
        ]), $product];
    }

    public function test_it_should_update_requested_order_item(): void
    {
        $this->login();
        [$orderItem] = $this->orderProduct(productAmount: 4, orderAmount: 1);
        $data = [UpdateRequest::AMOUNT => 4];

        $amount = $this->request($data, $orderItem)
            ->json(join('.', [
                OrderItems\PaginatorResource::DATA,
                OrderItems\DataResource::AMOUNT,
            ]));

        $this->assertSame($amount, 4);
        $this->assertDatabaseHas(OrderItem::TABLE, [
            OrderItem::ID => $orderItem->getId(),
            OrderItem::AMOUNT => $amount,
        ]);
    }

    public function test_it_should_response_correctly(): void
    {
        $this->login();
        [$orderItem] = $this->orderProduct(productAmount: 4, orderAmount: 1);
        $data = [UpdateRequest::AMOUNT => 4];

        $response = $this->request($data, $orderItem);

        $response->assertOk();
    }

    private function request(
        array $data,
        mixed $orderItem = null
    ): TestResponse {
        return $this->patchJson(
            route('api.v1.order-items.update', [
                'orderItem' => $orderItem ?? createOrderItem(),
            ]),
            data: $data
        );
    }

    private function login(): User
    {
        return $this->letsBe(createUser());
    }
}
