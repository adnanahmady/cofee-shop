<?php

namespace Tests\Unit\Price;

use App\Models\Currency;
use App\Repositories\CurrencyRepository;
use App\Settings\Delegators\MainCurrency;
use App\Settings\SettingManager;
use App\ValueObjects\Shared\PriceObject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class PriceObjectTest extends TestCase
{
    use RefreshDatabase;

    // phpcs:ignore
    public function test_when_main_currency_is_not_set_the_default_should_be_used(): void
    {
        /** @var SettingManager $manager */
        $mainCurrency = new MainCurrency();
        createCurrency([
            Currency::CODE => $mainCurrency->default(),
            Currency::DECIMAL_PLACES => 0,
        ]);

        $object = new PriceObject(
            price: 37.4354,
            currency: createCurrency([
                Currency::DECIMAL_PLACES => 3,
            ])
        );

        $this->assertMatchesRegularExpression(
            '/' . $mainCurrency->default() . ' \d+/',
            $object->represent()
        );
    }

    // phpcs:ignore
    public function test_it_should_show_prices_in_the_admin_specified_currency(): void
    {
        /** @var SettingManager $manager */
        $manager = resolve(SettingManager::class);
        $mainCurrency = createCurrency([
            Currency::CODE => $code = 'IRR',
            Currency::DECIMAL_PLACES => 0,
        ]);
        $manager->set(new MainCurrency(value: $code));

        $object = new PriceObject(
            price: 37.4354,
            currency: createCurrency([
                Currency::DECIMAL_PLACES => 3,
            ])
        );

        $this->assertMatchesRegularExpression(
            '/' . $mainCurrency->getCode() . ' \d+/',
            $object->represent()
        );
    }

    #[TestWith([37.435478634152, 2, 37.43])]
    #[TestWith([3743547, 2, 37435.47])]
    public function test_it_should_present_itself_as_expected(
        mixed $price,
        mixed $decimalPlaces,
        mixed $expected
    ): void {
        CurrencyRepository::deleteAll();
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
