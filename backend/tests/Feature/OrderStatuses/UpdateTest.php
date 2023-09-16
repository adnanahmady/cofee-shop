<?php

namespace Tests\Feature\OrderStatuses;

use App\Enums\AbilityEnum;
use App\Http\Controllers\Api\V1\Orders\StatusController;
use App\Http\Requests\Api\V1\OrderStatuses\UpdateRequest;
use App\Models\OrderItem;
use App\Notifications\StatusChangeNotification;
use App\Repositories\OrderRepository;
use Illuminate\Notifications\SendQueuedNotifications;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\DataProvider;
use App\Http\Resources\Api\V1\Orders\{Updated, Shared};
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\User;
use App\Repositories\OrderStatusRepository;
use App\Support\OrderStateDeterminer\Values\PreparationValue;
use App\Support\Values\ValueInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\LetsBeTrait;

#[CoversClass(StatusController::class)]
#[CoversFunction('update')]
class UpdateTest extends TestCase
{
    use RefreshDatabase;
    use LetsBeTrait;

    public function test_it_should_push_user_notification_to_queue(): void
    {
        Queue::fake([SendQueuedNotifications::class]);
        $this->loginAsManager();
        $order = createOrder();

        $this->request(order: $order);

        Queue::assertPushed(
            SendQueuedNotifications::class,
            fn (SendQueuedNotifications $job) => (
                $job->notification::class === StatusChangeNotification::class
            )
        );
    }

    // phpcs:ignore
    public function test_it_should_notify_the_user_after_the_order_status_gets_changed(): void
    {
        Notification::fake();
        $repository = new OrderRepository();
        $this->loginAsManager();
        $order = createOrder();

        $this->request(order: $order);

        Notification::assertSentTo(
            $repository->getCustomer($order),
            StatusChangeNotification::class
        );
    }

    public static function dataProviderForValidationTest(): array
    {
        return [
            'at least forward or rollback should be passed' => [[]],
            'rollback should be boolean' => [[
                UpdateRequest::FORWARD => true,
                UpdateRequest::ROLLBACK => 'on',
            ]],
            'forward should be boolean' => [[
                UpdateRequest::FORWARD => 'yes',
                UpdateRequest::ROLLBACK => true,
            ]],
            'the user should specify that wants to rollback or go forward' => [[
                UpdateRequest::FORWARD => false,
                UpdateRequest::ROLLBACK => false,
            ]],
        ];
    }

    #[DataProvider('dataProviderForValidationTest')]
    public function test_data_validation(array $data): void
    {
        $this->loginAsManager();
        $order = createOrder();

        $response = $this->request(order: $order, data: $data);

        $response->assertUnprocessable();
        $this->assertCount(1, $response->json('errors'));
    }

    public function test_data_item_should_be_in_expected_form(): void
    {
        $this->withoutExceptionHandling();
        $this->loginAsManager();
        $order = createOrder();
        createOrderItem([OrderItem::ORDER => $order]);

        $items = $this->request(order: $order)->json(join('.', [
            Updated\PaginatorResource::DATA,
            Shared\DataResource::ITEMS,
        ]));

        $this->assertCount(1, $items);
        $this->assertArrayHasKeys([
            Shared\ItemResource::ITEM_ID,
            Shared\ItemResource::NAME,
            Shared\ItemResource::AMOUNT,
            Shared\ItemResource::UNIT_PRICE,
            Shared\ItemResource::PRICE,
        ], $items[0]);
    }

    public function test_status_should_only_show_the_expected_fields(): void
    {
        $this->withoutExceptionHandling();
        $this->loginAsManager();
        $order = createOrder();

        $status = $this->request(order: $order)->json(join('.', [
            Updated\PaginatorResource::DATA,
            Shared\DataResource::STATUS,
        ]));

        $this->assertArrayHasKeys([
            Shared\StatusResource::ID,
            Shared\StatusResource::NAME,
        ], $status);
        $this->assertSame(
            (string) new PreparationValue(),
            $status[Shared\StatusResource::NAME]
        );
        $this->assertArrayNotHasKeys([
            OrderStatus::CREATED_AT,
            OrderStatus::UPDATED_AT,
        ], $status);
    }

    public function test_response_should_be_in_expected_form(): void
    {
        $this->withoutExceptionHandling();
        $this->loginAsManager();
        $order = createOrder();

        $data = $this->request(order: $order)->json(
            Updated\PaginatorResource::DATA
        );

        $this->assertArrayHasKeys([
            Shared\DataResource::ORDER_ID,
            Shared\DataResource::ITEMS,
            Shared\DataResource::TOTAL_PRICE,
            Shared\DataResource::STATUS,
        ], $data);
    }

    // phpcs:ignore
    public function test_it_should_update_the_status_when_going_forward_is_approved(): void
    {
        $this->withoutExceptionHandling();
        $this->loginAsManager();
        $order = createOrder();
        $preparation = $this->createStatus(new PreparationValue());

        $this->request(order: $order, data: [UpdateRequest::FORWARD => true]);

        $this->assertDatabaseHas(Order::TABLE, [
            Order::ID => $order->getId(),
            Order::STATUS => $preparation->getId(),
        ]);
    }

    public function test_only_manager_can_access_the_system(): void
    {
        $this->login();

        $response = $this->request();

        $response->assertForbidden();
    }

    public function test_it_should_response_with_proper_status_code(): void
    {
        $this->withoutExceptionHandling();
        $this->loginAsManager();

        $response = $this->request();

        $response->assertOk();
    }

    private function request(
        Order $order = null,
        array $data = [UpdateRequest::FORWARD => true]
    ): TestResponse {
        $order = $order ?? createOrder();

        return $this->patchJson(
            route('api.v1.order-statuses.update', $order),
            data: $data
        );
    }

    private function loginAsManager(): User
    {
        return $this->login(abilities: [
            AbilityEnum::SetOrderStatus->slugify(),
        ]);
    }

    private function login(User $user = null, array $abilities = []): User
    {
        return $this->letsBe(
            user: $user ?? createUser(),
            abilities: $abilities
        );
    }

    public function createStatus(ValueInterface $status): OrderStatus
    {
        return (new OrderStatusRepository())->firstOrCreate($status);
    }
}
