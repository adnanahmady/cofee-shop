<?php

namespace App\Observers;

use App\Models\Order;
use App\Notifications\StatusChangeNotification;
use App\Repositories\OrderRepository;

class OrderObserver
{
    private readonly OrderRepository $orderRepository;

    public function __construct()
    {
        $this->orderRepository = new OrderRepository();
    }

    public function created(Order $order): void
    {
        $this->orderRepository
            ->getCustomer($order)
            ->notify(new StatusChangeNotification($order));
    }
}
