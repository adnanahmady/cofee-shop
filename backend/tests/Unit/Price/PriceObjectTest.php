<?php

namespace Tests\Unit\Price;

use App\Models\Currency;
use App\ValueObjects\Shared\PriceObject;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class PriceObjectTest extends TestCase
{
    #[TestWith([37.435478634152, 2, 37.43])]
    #[TestWith([3743547, 2, 37435.47])]
    public function test_it_should_present_itself_as_expected(
        mixed $price,
        mixed $decimalPlaces,
        mixed $expected
    ): void {
        $object = new PriceObject(
            price: $price,
            currency: $c = createCurrency([
                Currency::DECIMAL_PLACES => $decimalPlaces,
            ])
        );

        $this->assertSame(
            $c->getCode() . ' ' . $expected,
            $object->represent()
        );
    }

    #[TestWith([43044, 2, 430.44])]
    #[TestWith([43044, 3, 43.044])]
    #[TestWith([4344, 2, 43.44])]
    // phpcs:ignore
    public function test_it_can_convert_deformed_price_to_correctly_formed_number(
        int $price,
        int $decimalPlaces,
        int|float $expected
    ): void {
        $object = new PriceObject(
            price: $price,
            currency: createCurrency([
                Currency::DECIMAL_PLACES => $decimalPlaces,
            ])
        );

        $this->assertSame($expected, $object->toNormalForm());
    }

    #[TestWith([20.444, 1, 204])]
    #[TestWith([13.444, 3, 13444])]
    public function test_it_can_make_price_to_integer_form(
        int|float $price,
        int $decimalPlaces,
        int $expected
    ): void {
        $object = new PriceObject(
            price: $price,
            currency: createCurrency([
                Currency::DECIMAL_PLACES => $decimalPlaces,
            ])
        );

        $this->assertSame($expected, $object->toInteger());
    }
}
