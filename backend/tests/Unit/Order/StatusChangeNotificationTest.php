<?php

namespace Tests\Unit\Order;

use App\Notifications\StatusChangeNotification;
use App\Repositories\OrderRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusChangeNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_order_address_should_be_as_expected(): void
    {
        $this->withoutExceptionHandling();
        $order = createOrder();
        $notification = new StatusChangeNotification($order);
        $pattern = sprintf(
            '/<a href="%s" [\w\s="-_]+>See Order<\/a>/',
            renderString(
                $this->escapeEndpointCharacters(),
                ['order' => $order->getId()]
            )
        );

        $body = $notification->toMail($order)->render()->toHtml();

        $this->assertMatchesRegularExpression(
            $pattern,
            $body,
        );
    }

    private function escapeEndpointCharacters(): string
    {
        return escapeString(url(config(
            'notifications.order.status.change.endpoint'
        )));
    }

    public function test_it_should_notify_the_user_with_expected_context(): void
    {
        $repository = new OrderRepository();
        $order = createOrder();
        $notification = new StatusChangeNotification($order);

        $body = $notification->toMail($order)->render()->toHtml();

        $assertContains = fn (string $needle) => $this
            ->assertStringContainsString($needle, $body);
        $assertContains('You order is updated');
        $assertContains(sprintf(
            'Dear %s,',
            $repository->getCustomer($order)->getName()
        ));
        $assertContains('You can visit your order by bellow link');
        $assertContains('>See Order</a>');
    }
}
