<?php

namespace App\Http\Resources\Api\V1\Orders\Shared;

use App\Http\Resources\Api\V1\Shared\CustomizationResource;
use App\Http\Resources\Api\V1\SharedContracts\CustomizationContract;
use App\Models\Customization;
use App\Models\OrderItem;
use App\Repositories\OrderItemRepository;
use App\Support\Calculators\TotalPrice;
use App\Support\Calculators\TotalPriceInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource implements CustomizationContract
{
    public const ITEM_ID = 'item_id';
    public const NAME = 'name';
    public const AMOUNT = 'amount';
    public const UNIT_PRICE = 'unit_price';
    public const PRICE = 'price';

    /** @var OrderItem */
    public $resource;
    private TotalPriceInterface $totalPrice;
    private OrderItemRepository $orderItemRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->totalPrice = new TotalPrice();
        $this->orderItemRepository = new OrderItemRepository();
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            self::ITEM_ID => $this->resource->getId(),
            self::NAME => $this->orderItemRepository
                ->getProduct($this->resource)->getName(),
            self::AMOUNT => $this->resource->getAmount(),
            self::CUSTOMIZATIONS => $this->orderItemRepository
                ->getCustomizations($this->resource)
                ->map(fn (Customization $c) => new CustomizationResource($c)),
            self::UNIT_PRICE => $this->resource
                ->getPriceObject()->represent(),
            self::PRICE => $this->totalPrice->addPrices(
                $this->resource->getPriceObject(),
                $this->resource->getAmount()
            )->represent(),
        ];
    }
}
