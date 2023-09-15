<?php

namespace App\Repositories;

use App\Exceptions\Models\InvalidOrderItemAmountException;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderItemRepository
{
    private ProductRepository $productRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
    }

    public function getOptions(OrderItem $item): Collection
    {
        return $item->options;
    }

    public function getOptionsWithCustomization(OrderItem $item): Collection
    {
        return $item->load('options.customization')->options;
    }

    public function addOption(OrderItem $orderItem, int $optionId): void
    {
        $orderItem->options()->attach($optionId);
    }

    public function delete(OrderItem $orderItem): bool|null
    {
        try {
            DB::beginTransaction();
            $this->productRepository->orderProduct(
                $this->getProduct($orderItem),
                $orderItem->getAmount() * -1
            );
            $removed = $orderItem->delete();
            DB::commit();

            return $removed;
        } catch (InvalidOrderItemAmountException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateAmount(
        OrderItem $orderItem,
        int $amount
    ): OrderItem {
        try {
            DB::beginTransaction();
            $this->productRepository->orderProduct(
                $this->getProduct($orderItem),
                $amount - $orderItem->getAmount()
            );
            $orderItem->setAmount($amount);
            $orderItem->save();
            DB::commit();

            return $orderItem->fresh();
        } catch (InvalidOrderItemAmountException $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getProduct(OrderItem $orderItem): Product
    {
        return $orderItem->product;
    }
}
