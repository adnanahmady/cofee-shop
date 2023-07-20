<?php

namespace App\Http\Resources\Api\V1\Orders\Shared;

use App\Http\Resources\Api\V1\Shared\DeliveryTypeResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\OrderRepository;
use App\Support\Calculators\TotalPrice;
use App\Support\Calculators\TotalPriceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DataResource extends JsonResource
{
    public const ORDER_ID = 'order_id';
    public const ITEMS = 'items';
    public const TOTAL_PRICE = 'total_price';
    public const STATUS = 'status';
    public const DELIVERY_TYPE = 'delivery_type';

    /** @var Order */
    public $resource;
    private OrderRepository $orderRepository;
    private TotalPriceInterface $totalPrice;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->orderRepository = new OrderRepository();
        $this->totalPrice = new TotalPrice();
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            self::ORDER_ID => $this->resource->getId(),
            self::ITEMS => $this->orderRepository->getItems($this->resource)
                ->map(function (OrderItem $item) {
                    $this->totalPrice->addPrices(
                        $item->getPriceObject(),
                        $item->getAmount()
                    );

                    return $item;
                })
                ->map(fn ($item) => new ItemResource($item)),
            self::TOTAL_PRICE => $this->totalPrice->represent(),
            self::STATUS => new StatusResource(
                $this->orderRepository->getStatus($this->resource),
            ),
            self::DELIVERY_TYPE => new DeliveryTypeResource(
                $this->orderRepository->getDeliveryType($this->resource),
            ),
        ];
    }
}
