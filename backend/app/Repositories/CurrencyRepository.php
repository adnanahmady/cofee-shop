<?php

namespace App\Repositories;

use App\Models\Currency;

class CurrencyRepository
{
    public function findByCode(string $code): null|Currency
    {
        return Currency::where(Currency::CODE, $code)->first();
    }

    public static function deleteAll(): void
    {
        Currency::all()->each->delete();
    }
}
