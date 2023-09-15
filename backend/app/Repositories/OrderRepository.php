<?php

namespace App\Repositories;

use App\Exceptions\Models\InvalidOrderItemAmountException;
use App\Interfaces\IdInterface;
use App\Models\DeliveryType;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Models\User;
use App\Support\OrderStateDeterminer\Values\WaitingValue;
use App\Support\RequestMappers\Orders\ProductIteratorInterface;
use App\Support\RequestMappers\Orders\ProductMapperInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    private ProductRepository $productRepository;
    private OrderStatusRepository $statusRepository;
    private OrderItemRepository $orderItemRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->statusRepository = new OrderStatusRepository();
        $this->orderItemRepository = new OrderItemRepository();
    }

    public function doesBelongToUser(Order $order, IdInterface $user): bool
    {
        return $user->getId() === $order->user->getId();
    }

    public function updateDeliveryType(
        Order $order,
        IdInterface $deliveryType
    ): Order {
        $order->setDeliveryType($deliveryType);
        $order->save();

        return $order;
    }

    public function getCustomer(Order $order): User
    {
        return $order->user;
    }

    public function changeStatus(Order $order, OrderStatus $status): void
    {
        $order->status()->associate($status)->save();
    }

    public function getPaginated(int $page, int $perPage): LengthAwarePaginator
    {
        return Order::query()->paginate(perPage: $perPage, page: $page);
    }

    public function getPaginatedForUser(
        User $user,
        int $page,
        int $perPage
    ): LengthAwarePaginator {
        return Order::query()
            ->where(Order::USER, $user->getId())
            ->paginate(perPage: $perPage, page: $page);
    }

    public function getItems(Order $order): Collection
    {
        return $order->items;
    }

    public function getStatus(Order $order): ?OrderStatus
    {
        return $order?->status;
    }

    public function getDeliveryType(Order $order): ?DeliveryType
    {
        return $order?->deliveryType;
    }

    /**
     * @throws InvalidOrderItemAmountException
     */
    public function orderProducts(
        User $user,
        ProductIteratorInterface $products,
        IdInterface $deliveryType,
    ): Order {
        try {
            DB::beginTransaction();
            $order = $this->createOrder($user, $deliveryType);

            foreach ($products as $product) {
                $orderItem = $this->addProduct($order, $product);

                foreach ($product->getCustomizations() as $selectedOption) {
                    $this->orderItemRepository->addOption(
                        $orderItem,
                        $selectedOption->getOptionId()
                    );
                }
            }
            DB::commit();

            return $order;
        } catch (InvalidOrderItemAmountException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function createOrder(
        User $user,
        IdInterface $deliveryType
    ): Order {
        $order = new Order();
        $order->setUser($user);
        $order->setStatus(
            $this->statusRepository->firstOrCreate(
                new WaitingValue()
            )
        );
        $order->setDeliveryType($deliveryType);
        $order->save();

        return $order;
    }

    private function addProduct(
        Order $order,
        ProductMapperInterface $mapper
    ): OrderItem {
        $product = $mapper->getProduct();
        $amount = $mapper->getAmount();
        $this->productRepository->orderProduct($product, $amount);
        $price = $product->getPriceObject();

        foreach ($mapper->getCustomizations() as $customization) {
            $price = $price->sum(
                $customization->getOption()->getPriceObject()
            );
        }

        return $order->items()->create([
            OrderItem::PRODUCT => $product->getId(),
            OrderItem::AMOUNT => $amount,
            OrderItem::CURRENCY => $price->getCurrency()->getId(),
            OrderItem::PRICE => $price->getPrice(),
        ]);
    }
}
