<?php

namespace Tests\Feature\Orders;

use App\Enums\AbilityEnum;
use App\Http\Requests\Api\V1\Orders\GetListRequest;
use App\Http\Resources\Api\V1\Orders\List;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Support\Values\Pagination\PerPageValue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;
use Tests\Traits\LetsBeTrait;

class GetListTest extends TestCase
{
    use RefreshDatabase;
    use LetsBeTrait;

    private UserRepository $userRepository;

    public function test_default_value_for_page_should_be_expected(): void
    {
        $this->withoutExceptionHandling();
        createOrder(count: 5);
        $this->login();

        $response = $this->request(perPage: 2)->json();

        $this->assertSame(
            1,
            $response['meta']['current_page']
        );
    }

    public function test_default_value_for_per_page_should_be_expected(): void
    {
        $this->withoutExceptionHandling();
        $perPageValue = new PerPageValue();
        createOrder(count: 5);
        $this->login();

        $response = $this->request(page: 3)->json();

        $this->assertSame(
            $perPageValue->represent(),
            $response['meta']['per_page']
        );
    }

    public static function dataProviderForParameterValidationTest(): array
    {
        return [
            'per page should be integer' => [['perPage' => 'a']],
            'page should be integer' => [['page' => 'a']],
        ];
    }

    #[DataProvider('dataProviderForParameterValidationTest')]
    public function test_parameter_validation(array $params): void
    {
        createOrder(count: 3);
        $manager = $this->loginAsManger();
        $this->userRepository->saveOrders($manager, createOrder(count: 2));

        $response = $this->request(...$params);

        $response->assertUnprocessable();
        $this->assertCount(1, $response->json('errors'));
    }

    public function test_user_should_be_able_to_paginate(): void
    {
        $this->withoutExceptionHandling();
        createOrder(count: 3);
        $manager = $this->loginAsManger();
        $this->userRepository->saveOrders($manager, createOrder(count: 2));

        $response = $this->request(page: 3, perPage: 2)->json();

        $this->assertSame(3, $response['meta']['current_page']);
        $this->assertSame(2, $response['meta']['per_page']);
    }

    public function test_paginated_response_should_contain_expected_fields(): void
    {
        $this->withoutExceptionHandling();
        createOrder(count: 3);
        $manager = $this->loginAsManger();
        $this->userRepository->saveOrders($manager, createOrder(count: 2));

        $response = $this->request()->json();

        $this->assertIsArray($response[List\PaginatorCollection::DATA]);
        $this->assertIsArray($response['links']);
        $this->assertIsArray($meta = $response['meta']);
        $this->assertIsInt($meta['current_page']);
        $this->assertIsInt($meta['from']);
        $this->assertIsInt($meta['to']);
        $this->assertIsInt($meta['total']);
        $this->assertIsInt($meta['per_page']);
    }

    public function test_the_response_should_be_paginated(): void
    {
        $this->withoutExceptionHandling();
        createOrder(count: 3);
        $manager = $this->loginAsManger();
        $this->userRepository->saveOrders($manager, createOrder(count: 2));

        $response = $this->request()->json();

        $this->assertGreaterThan(1, count($response));
    }

    public function test_the_manager_can_see_the_list_or_all_orders_regardless_of_the_user(): void
    {
        $this->withoutExceptionHandling();
        createOrder(count: 3);
        $manager = $this->loginAsManger();
        $this->userRepository->saveOrders($manager, createOrder(count: 2));

        $data = $this->request()->json(List\PaginatorCollection::DATA);

        $this->assertCount(5, $data);
    }

    public function loginAsManger(): User
    {
        return $this->login(abilities: [
            AbilityEnum::SetOrderStatus->slugify(),
        ]);
    }

    public function test_order_item_should_have_expected_fields(): void
    {
        $this->withoutExceptionHandling();
        [$orderItems] = $this->setItemsOfOrders();

        $item = $this->request()->json(join('.', [
            List\PaginatorCollection::DATA,
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
            List\PaginatorCollection::DATA,
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
            List\PaginatorCollection::DATA,
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

        $data = $this->request()->json(List\PaginatorCollection::DATA);

        $this->assertCount($userOrders->count(), $data);
    }

    public function test_it_responses_correctly(): void
    {
        $this->login();

        $response = $this->request();

        $response->assertOk();
    }

    private function login(array $abilities = []): User
    {
        return $this->letsBe(createUser(), abilities: $abilities);
    }

    private function request(mixed $page = null, mixed $perPage = null): TestResponse
    {
        return $this->getJson(route(
            name: 'api.v1.orders.index',
            parameters: [
                GetListRequest::PAGE => $page,
                GetListRequest::PER_PAGE => $perPage,
            ]
        ));
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = new UserRepository();
    }
}
