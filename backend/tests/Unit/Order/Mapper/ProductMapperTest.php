<?php

namespace Tests\Unit\Order\Mapper;

use App\Exceptions\InvalidValueException;
use App\Http\Requests\Api\V1\Orders\StoreRequest;
use App\Support\RequestMappers\Orders\ProductMapper;
use Tests\TestCase;

class ProductMapperTest extends TestCase
{
    // phpcs:ignore
    public function test_when_non_array_data_is_given_the_customizations_method_should_throw_exception(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(InvalidValueException::class);

        $mapper = new ProductMapper(item: [
            StoreRequest::PRODUCT_ID => 1,
            StoreRequest::AMOUNT => 1,
            StoreRequest::CUSTOMIZATIONS => 1,
        ]);

        $mapper->getCustomizations();
    }
}
