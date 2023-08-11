<?php

namespace App\Notifications;

use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusChangeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private readonly OrderRepository $orderRepository;

    /**
     * Create a new notification instance.
     */
    public function __construct(private readonly Order $order)
    {
        $this->orderRepository = new OrderRepository();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->line('You order is updated')
            ->line(__('Dear :user,', [
                'user' => $this->getName(),
            ]))
            ->line('You can visit your order by bellow link')
            ->action('See Order', $this->getShowOrderUri());
    }

    private function getName(): string
    {
        return $this->orderRepository->getCustomer($this->order)->getName();
    }

    /**
     * The link needs to point to a frontend page so the
     * customer can visit its order properly instead of
     * an api page, since this application is just a
     * backend side the url for showing the order should
     * be based on the frontend and also the environment
     * of the application, the url address should be set
     * on `.env` file and then be used in here and usually
     * the order id is required for this action, therefore
     * the order is passed to the notification using the
     * constructor.
     */
    private function getShowOrderUri(): string
    {
        return url(renderString($this->getFormedEndpoint(), [
            'order' => $this->order->getId(),
        ]));
    }

    private function getFormedEndpoint(): mixed
    {
        return config('notifications.order.status.change.endpoint') ?? '/';
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
        ];
    }
}
