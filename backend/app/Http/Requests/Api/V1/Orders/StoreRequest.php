<?php

namespace App\Http\Requests\Api\V1\Orders;

use App\Http\Requests\Api\V1\AbstractFormRequest;
use App\Interfaces\IdInterface;
use App\Models\DeliveryType;
use App\Models\Option;
use App\Models\Product;
use App\Repositories\DeliveryTypeRepository;
use App\Support\RequestMappers\Orders\ProductIteratorInterface;
use App\Support\RequestMappers\Orders\ProductsIterator;

class StoreRequest extends AbstractFormRequest
{
    public const PRODUCTS = 'products';
    public const PRODUCT_ID = 'id';
    public const AMOUNT = 'amount';
    public const DELIVERY_TYPE = 'delivery_type_id';
    public const CUSTOMIZATIONS = 'customizations';
    public const OPTION_ID = 'option_id';

    public function rules(): array
    {
        return [
            self::DELIVERY_TYPE => sprintf(
                'required|integer|exists:%s,%s',
                DeliveryType::TABLE,
                DeliveryType::ID
            ),
            self::PRODUCTS => 'required|array|min:1',
            $this->productsItem(self::PRODUCT_ID) => sprintf(
                'required|int|exists:%s,%s',
                Product::class,
                Product::ID
            ),
            $this->productsItem(self::AMOUNT) => 'int',
            $this->productsItem(self::CUSTOMIZATIONS) => 'array|min:1',
            $this->productsItem(
                self::CUSTOMIZATIONS . '.*.' . self::OPTION_ID
            ) => sprintf(
                'required|int|exists:%s,%s',
                Option::TABLE,
                Option::ID
            ),
        ];
    }

    private function productsItem(string $key): string
    {
        return sprintf('%s.*.%s', self::PRODUCTS, $key);
    }

    public function getProductIdPath(): string
    {
        return $this->productsItem(self::PRODUCT_ID);
    }

    public function getProducts(): ProductIteratorInterface
    {
        return new ProductsIterator($this->get(self::PRODUCTS));
    }

    public function getDeliveryType(): IdInterface
    {
        $repository = new DeliveryTypeRepository();
        $id = $this->get(self::DELIVERY_TYPE);

        return $repository->findById($id);
    }
}
