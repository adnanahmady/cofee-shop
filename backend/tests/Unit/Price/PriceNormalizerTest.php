<?php

namespace Tests\Unit\Price;

use App\Support\Converters\PriceNormalizer;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class PriceNormalizerTest extends TestCase
{
    #[TestWith([3142040780.80, 3, 31420407.808])]
    #[TestWith([31420407808.0, 3, 31420407.808])]
    #[TestWith([1344, 2, 13.44])]
    #[TestWith([1340, 2, 13.4])]
    #[TestWith([434456, 0, 434456])]
    #[TestWith([4345634534, 5, 43456.34534])]
    #[TestWith([4345634536, 5, 43456.34536])]
    #[TestWith([4345634500, 5, 43456.345])]
    // phpcs:ignore
    public function test_it_should_reform_the_denormalized_price_into_normalized_form(
        float|int $price,
        int $decimalPlaces,
        float|int $expected
    ): void {
        $converter = new PriceNormalizer(
            price: $price,
            decimalPlaces: $decimalPlaces
        );

        $this->assertEquals($expected, $converter->normalize());
    }
}
