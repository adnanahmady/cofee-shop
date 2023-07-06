<?php

namespace App\Repositories;

use App\Exceptions\Models\InvalidOrderItemAmountException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\Product;
use App\Models\User;
use App\Support\RequestMappers\Orders\DataMapperInterface;
use App\Support\Values\OrderStatuses\WaitingValue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    private ProductRepository $productRepository;
    private OrderStatusRepository $statusRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->statusRepository = new OrderStatusRepository();
    }

    public function getItems(Order $order): Collection
    {
        return $order->items;
    }

    public function getStatus(Order $order): OrderStatus
    {
        return $order->status;
    }

    public function orderProducts(
        User $user,
        DataMapperInterface $products
    ): Order {
        try {
            DB::beginTransaction();
            $order = $this->createOrder($user);

            foreach ($products as $item) {
                $this->addProduct(
                    $order,
                    $item->getProduct(),
                    $item->getAmount()
                );
            }
            DB::commit();

            return $order;
        } catch (InvalidOrderItemAmountException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function createOrder(User $user): Order
    {
        $order = new Order();
        $order->setUser($user);
        $order->setStatus(
            $this->statusRepository->firstOrCreate(
                new WaitingValue()
            )
        );
        $order->save();

        return $order;
    }

    private function addProduct(
        Order $order,
        Product $product,
        int $amount
    ): void {
        $this->productRepository->orderProduct($product, $amount);
        $price = $product->getPriceObject();
        $order->items()->create([
            OrderItem::PRODUCT => $product->getId(),
            OrderItem::AMOUNT => $amount,
            OrderItem::CURRENCY => $price->getCurrency()->getId(),
            OrderItem::PRICE => $price->getPrice(),
        ]);
    }
}
