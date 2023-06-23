<?php

namespace Tests\Unit\Price;

use App\Models\Currency;
use App\Support\Calculators\TotalPrice;
use App\Support\Exchangers\PriceExchanger;
use App\ValueObjects\Shared\PriceInterface;
use App\ValueObjects\Shared\PriceObject;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class TotalPriceTest extends TestCase
{
    public function test_it_can_get_the_total_of_specified_number_of_prices(): void
    {
        $total = 0;
        $totalPrice = new TotalPrice();
        $eur = $this->getEurCurrency();
        $price1 = new PriceObject(30, $eur);
        $price2 = new PriceObject(20, $eur);
        $totalPrice->addPrices($price1, $p1Count = 20);
        $totalPrice->addPrices($price2, $p2Count = 9);
        $total += $this->addPrices($price1, $p1Count);
        $total += $this->addPrices($price2, $p2Count);

        $price = $totalPrice->getPrice();

        $this->assertEquals($total, $price);
    }

    private function addPrices(PriceInterface $price, int $count): int
    {
        $total = 0;

        for ($i = 0; $i < $count; ++$i) {
            $total += $price->getPrice();
        }

        return $total;
    }

    public function test_it_can_represent_the_total_amount(): void
    {
        $eur = $this->getEurCurrency();
        $irr = $this->getIrrCurrency();
        $price1 = new PriceObject(30, $eur);
        $price2 = new PriceObject(250000, $irr);
        $totalPrice = new TotalPrice();

        $totalPrice->addPrice($price1);
        $totalPrice->addPrice($price2);

        $this->assertSame(
            sprintf(
                '%s %s',
                $eur->getCode(),
                round($totalPrice->getPrice(), $eur->getDecimalPlaces())
            ),
            $totalPrice->represent()
        );
    }

    public function test_it_can_sum_in_different_currency_units(): void
    {
        $eur = $this->getEurCurrency();
        $irr = $this->getIrrCurrency();
        $price1 = new PriceObject(30, $eur);
        $price2 = new PriceObject(250000, $irr);
        $totalPrice = new TotalPrice();

        $totalPrice->addPrice($price1);
        $totalPrice->addPrice($price2);
        $exchanger = new PriceExchanger($price2);
        $exchangedPrice = $exchanger->exchange(to: $eur);

        $this->assertEquals(
            $price1->getPrice() + $exchangedPrice->getPrice(),
            $totalPrice->getPrice()
        );
        $this->assertSame(
            $totalPrice->getCurrency()->getCode(),
            $eur->getCode()
        );
    }

    public function test_it_can_sum_prices(): void
    {
        $currency = $this->getUsdCurrency();
        $price1 = new PriceObject(30, $currency);
        $price2 = new PriceObject(25, $currency);
        $totalPrice = new TotalPrice();

        $totalPrice->addPrice($price1);
        $totalPrice->addPrice($price2);

        $this->assertEquals(
            $price1->getPrice() + $price2->getPrice(),
            $totalPrice->getPrice()
        );
        $this->assertSame(
            $totalPrice->getCurrency()->getCode(),
            $currency->getCode()
        );
    }

    private function getEurCurrency(): Currency
    {
        return createCurrency([Currency::CODE => 'EUR']);
    }

    private function getIrrCurrency(): Currency
    {
        return createCurrency([Currency::CODE => 'IRR']);
    }

    private function getUsdCurrency(): Collection|Currency
    {
        return createCurrency([Currency::CODE => 'USD']);
    }
}
