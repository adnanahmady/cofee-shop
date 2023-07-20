<?php

namespace App\Http\Requests\Api\V1\Orders;

use App\Http\Requests\Api\V1\AbstractFormRequest;
use App\Interfaces\IdInterface;
use App\Models\DeliveryType;
use App\Models\Product;
use App\Repositories\DeliveryTypeRepository;
use App\Support\RequestMappers\Orders\DataMapperInterface;
use App\Support\RequestMappers\Orders\ProductsMapper;

class StoreRequest extends AbstractFormRequest
{
    public const PRODUCTS = 'products';
    public const PRODUCT_ID = 'id';
    public const AMOUNT = 'amount';
    public const DELIVERY_TYPE = 'delivery_type_id';

    public function rules(): array
    {
        return [
            self::PRODUCTS => 'required|array|min:1',
            self::DELIVERY_TYPE => sprintf(
                'required|integer|exists:%s,%s',
                DeliveryType::TABLE,
                DeliveryType::ID
            ),
            $this->getProductIdPath() => sprintf(
                'required|int|exists:%s,%s',
                Product::class,
                Product::ID
            ),
            $this->getProductKeyPath(self::AMOUNT) => 'int',
        ];
    }

    public function getProductIdPath(): string
    {
        return $this->getProductKeyPath(self::PRODUCT_ID);
    }

    private function getProductKeyPath(string $key): string
    {
        return sprintf('%s.*.%s', self::PRODUCTS, $key);
    }

    public function getProducts(): DataMapperInterface
    {
        return new ProductsMapper($this->{self::PRODUCTS});
    }

    public function getDeliveryType(): IdInterface
    {
        $repository = new DeliveryTypeRepository();
        $id = $this->get(self::DELIVERY_TYPE);

        return $repository->findById($id);
    }
}
