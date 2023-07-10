<?php

namespace App\Http\Resources\Api\V1\Orders\List;

use App\Http\Resources\Api\V1\Orders\Shared\StatusResource;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\OrderRepository;
use App\Support\Calculators\TotalPrice;
use App\Support\Calculators\TotalPriceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public const ID = 'id';
    public const ITEMS = 'items';
    public const ORDERED_AT = 'ordered_at';
    public const UPDATED_AT = 'updated_at';
    public const TOTAL_PRICE = 'total_price';
    public const STATUS = 'status';

    /** @var Order */
    public $resource;
    private OrderRepository $repository;
    private TotalPriceInterface $totalPrice;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->repository = new OrderRepository();
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
            self::ID => $this->resource->getId(),
            self::ITEMS => $this->repository->getItems($this->resource)
                ->map(function (OrderItem $oi) {
                    $this->totalPrice->addPrices(
                        $oi->getPriceObject(),
                        $oi->getAmount()
                    );

                    return $oi;
                })
                ->map(fn (OrderItem $oi) => new OrderItemResource($oi)),
            self::TOTAL_PRICE => $this->totalPrice->represent(),
            self::STATUS => new StatusResource(
                $this->repository->getStatus($this->resource)
            ),
            self::ORDERED_AT => $this->resource->getCreatedAt(),
            self::UPDATED_AT => $this->resource->getUpdatedAt(),
        ];
    }
}
