<?php

namespace App\Http\Resources\Api\V1\Products\List;

use App\Models\Customization;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public const PRODUCT_ID = 'product_id';
    public const NAME = 'name';
    public const PRICE = 'price';
    public const AMOUNT = 'amount';
    public const CUSTOMIZATIONS = 'customizations';

    /** @var Product */
    public $resource;
    private readonly ProductRepository $productRepository;

    public function __construct($resource)
    {
        parent::__construct($resource);
        $this->productRepository = new ProductRepository();
    }

    public function toArray(Request $request): array
    {
        return [
            self::PRODUCT_ID => $this->resource->getId(),
            self::NAME => $this->resource->getName(),
            self::PRICE => $this->resource->getPriceObject()->represent(),
            self::AMOUNT => $this->resource->getAmount(),
            self::CUSTOMIZATIONS => $this->productRepository
                ->getCustomizations($this->resource)
                ->map(fn (Customization $c) => new CustomizationResource($c)),
        ];
    }
}
