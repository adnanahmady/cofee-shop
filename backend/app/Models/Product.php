<?php

namespace App\Models;

use App\Contracts\Models\Fields\IdContract;
use App\Contracts\Models\Fields\NameContract;
use App\Traits\Models\Fields\HasNameTrait;
use App\Traits\Models\HasPriceAndCurrencyTrait;
use App\Traits\Models\HasPriceObjectPropertyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model implements
    IdContract,
    NameContract
{
    use HasFactory;
    use HasNameTrait;
    use HasPriceAndCurrencyTrait;
    use HasPriceObjectPropertyTrait;

    public const TABLE = 'products';
    public const AMOUNT = 'amount';
    public const PRICE = 'price';
    public const CURRENCY = 'currency_id';

    protected $table = self::TABLE;

    protected $primaryKey = self::ID;

    protected $fillable = [
        self::NAME,
        self::AMOUNT,
        self::PRICE,
        self::CURRENCY,
    ];

    protected function getPriceName(): string
    {
        return self::PRICE;
    }

    protected function getCurrencyName(): string
    {
        return self::CURRENCY;
    }

    public function getId(): int
    {
        return $this->{self::ID};
    }

    public function getAmount(): int
    {
        return $this->{self::AMOUNT};
    }

    public function setAmount(int $amount): void
    {
        $this->{self::AMOUNT} = $amount;
    }
}
