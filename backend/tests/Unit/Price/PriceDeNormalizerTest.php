<?php

namespace Tests\Unit\Price;

use App\Support\Converters\PriceDeNormalizer;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class PriceDeNormalizerTest extends TestCase
{
    #[TestWith([13.44, 2, 1344])]
    #[TestWith([13.404, 2, 1340])]
    #[TestWith([434456.345345, 0, 434456])]
    #[TestWith([43456.345345, 5, 4345634534])]
    #[TestWith([43456.345366, 5, 4345634536])]
    #[TestWith([43456.345, 5, 4345634500])]
    public function test_it_can_convert_the_given_price_to_integer_form(
        int|float $price,
        int $decimalPlaces,
        int $expected
    ): void {
        $converter = new PriceDeNormalizer(
            price: $price,
            decimalPlaces: $decimalPlaces
        );

        $this->assertSame(
            $expected,
            $converter->denormalize()
        );
    }
}
