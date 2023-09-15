<?php

namespace Tests\Unit\Price;

use App\Api\Exchanges;
use App\Models\Currency;
use App\Support\Exchangers\PriceExchanger;
use App\ValueObjects\Shared\PriceInterface;
use App\ValueObjects\Shared\PriceObject;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class PriceExchangerTest extends TestCase
{
    // phpcs:ignore
    public function test_it_should_exchange_from_a_currency_to_another_correctly(): void
    {
        $irr = createCurrency([
            Currency::CODE => 'irr',
            Currency::DECIMAL_PLACES => 3,
        ]);

        $price = new PriceObject($p = 1234, $irr);

        $newPrice = $this->exchange($price, $irr);

        $this->assertSame($p, $newPrice->getPrice());
    }

    public function test_zero_price_is_just_not_exchanged(): void
    {
        $irr = $this->getIrrCurrency();
        $eur = $this->getEurCurrency();
        $price = new PriceObject(0, $irr);

        $newPrice = $this->exchange($price, $eur);

        $this->assertEquals(0, $newPrice->getPrice());
        $this->assertSame($eur, $newPrice->getCurrency());
    }

    // phpcs:ignore
    public function test_it_can_change_from_non_base_currency_to_another_currency(): void
    {
        $irr = $this->getIrrCurrency();
        $eur = $this->getEurCurrency();
        $price = new PriceObject(300000, $irr);
        $exchanged = $this->exchangeExample($price, $eur);

        $newPrice = $this->exchange($price, $eur);

        $this->assertEquals($exchanged, $newPrice->getPrice());
    }

    public function test_it_can_exchange_price_currency(): void
    {
        $usd = $this->usdCurrency();
        $eur = $this->getEurCurrency();
        $price = new PriceObject(30, $usd);
        $exchanged = $this->exchangeExample($price, $eur);

        $newPrice = $this->exchange($price, $eur);

        $this->assertEquals($exchanged, $newPrice->getPrice());
    }

    private function getIrrCurrency(): Collection|Currency
    {
        return createCurrency([Currency::CODE => 'IRR']);
    }

    private function usdCurrency(): Collection|Currency
    {
        return createCurrency([Currency::CODE => 'USD']);
    }

    private function getEurCurrency(): Collection|Currency
    {
        return createCurrency([Currency::CODE => 'EUR']);
    }

    private function exchange(
        PriceObject $price,
        Currency $eur
    ): PriceInterface {
        $exchanger = new PriceExchanger($price);

        return $exchanger->exchange(to: $eur);
    }

    private function exchangeExample(
        PriceObject $price,
        Currency $eur
    ): int|float {
        $exchanges = new Exchanges();
        $code = $price->getCurrency()->getCode();
        $toBaseCurrency = $price->getPrice() * $exchanges->getRate($code);

        return $toBaseCurrency / $exchanges->getRate($eur->getCode());
    }
}
