<?php

namespace Tests\Unit\Models;

use App\Exceptions\Models\UnavailableRelationException;
use App\Models\Currency;
use App\Models\Product;
use Tests\TestCase;
use Tests\Traits\MigrateDatabaseTrait;

class ProductTest extends TestCase
{
    use MigrateDatabaseTrait;

    public function test_it_should_throw_exception_if_currency_is_not_being_set(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(UnavailableRelationException::class);

        $product = new Product();
        $product->{Product::PRICE} = 1000;
    }

    public function test_it_returns_price_in_expected_form(): void
    {
        $product = createProduct([
            Product::PRICE => 114433,
            Product::CURRENCY => createCurrency([
                Currency::DECIMAL_PLACES => 0,
            ]),
        ]);

        $price = $product->getPriceObject()->getPrice();

        $this->assertSame(114433, $price);
    }

    public function test_it_stores_price_as_integer_in_database_with_expected_formula(): void
    {
        $currency = createCurrency([Currency::DECIMAL_PLACES => 4]);
        $fields = [
            Product::PRICE => $price = 13.43446664,
            Product::CURRENCY => $currency,
        ];

        createProduct($fields);

        $this->assertDatabaseHas(Product::TABLE, [
            Product::PRICE => round($price * pow(10, $currency->getDecimalPlaces())),
        ]);
    }
}
