<?php

namespace App\Support\DecisionMakers;

use App\Interfaces\SettingInterface;
use App\Models\Currency;
use App\Repositories\CurrencyRepository;
use App\Settings\Delegators\MainCurrency;
use App\Settings\SettingManager;

class CurrencyDecisionMaker
{
    private readonly SettingInterface $mainCurrency;
    private readonly CurrencyRepository $currencyRepository;

    public function __construct(private readonly Currency $currency)
    {
        $this->mainCurrency = $this->getMainCurrency();
        $this->currencyRepository = new CurrencyRepository();
    }

    private function getManager(): SettingManager
    {
        return resolve(SettingManager::class);
    }

    private function getMainCurrency(): SettingInterface
    {
        $manager = $this->getManager();

        return $manager->get(new MainCurrency());
    }

    public function decide(): Currency
    {
        if ($this->isMainCurrencySet()) {
            $mainCurrencyValue = $this->currencyRepository->findByCode(
                $this->mainCurrency->value()
            );
        }

        if ($this->isMainCurrencyDefaultAvailable()) {
            $mainCurrencyDefault = $this->currencyRepository->findByCode(
                $this->mainCurrency->default()
            );
        }

        return $mainCurrencyValue ?? $mainCurrencyDefault ?? $this->currency;
    }

    private function isMainCurrencySet(): bool
    {
        return
            null !== $this->mainCurrency->value()
            && $this->mainCurrency->value() !== $this->currency->getCode();
    }

    private function isMainCurrencyDefaultAvailable(): bool
    {
        return
            null === $this->mainCurrency->value()
            && null !== $this->mainCurrency->default()
            && $this->mainCurrency->default() !== $this->currency->getCode();
    }
}
