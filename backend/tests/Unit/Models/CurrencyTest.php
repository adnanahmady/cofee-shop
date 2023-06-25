<?php

namespace Tests\Unit\Models;

use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_should_show_default_instead_of_null_value(): void
    {
        $currency = new Currency();
        $currency->setName('US Dollar');
        $currency->setCode('USD');
        $currency->save();

        $decimalPlaces = $currency->getDecimalPlaces();

        $this->assertSame(0, $decimalPlaces);
    }

    public function test_it_should_show_integer_for_decimal_places(): void
    {
        $fields = [Currency::DECIMAL_PLACES => $decimalPlaces = 2];

        $currency = createCurrency($fields);

        $this->assertSame($decimalPlaces, $currency->getDecimalPlaces());
    }
}
