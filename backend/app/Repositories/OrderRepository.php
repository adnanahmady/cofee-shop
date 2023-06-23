<?php

namespace App\Repositories;

use App\Exceptions\Models\InvalidOrderItemAmountException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Support\RequestMappers\Orders\DataMapperInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    private ProductRepository $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }

    public function getItems(Order $order): Collection
    {
        return $order->items;
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
        $order->save();

        return $order;
    }

    private function addProduct(
        Order $order,
        Product $product,
        int $amount
    ): void {
        $this->productRepository->orderAmount($product, $amount);
        $price = $product->getPriceObject();
        $order->items()->create([
            OrderItem::PRODUCT => $product->getId(),
            OrderItem::AMOUNT => $amount,
            OrderItem::CURRENCY => $price->getCurrency()->getId(),
            OrderItem::PRICE => $price->getPrice(),
        ]);
    }
}
