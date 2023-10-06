<?php

namespace App\Settings\Delegators;

class MainCurrency extends AbstractSetting
{
    public function name(): string
    {
        return 'main.currency.setting';
    }

    public function default(): null|string
    {
        return 'USD';
    }
}
