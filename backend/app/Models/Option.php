<?php

namespace App\Models;

use App\Contracts\Models\Fields\IdContract;
use App\Contracts\Models\Fields\NameContract;
use App\Traits\Models\Fields\HasIdTrait;
use App\Traits\Models\Fields\HasNameTrait;
use App\Traits\Models\HasPriceAndCurrencyTrait;
use App\Traits\Models\HasPriceObjectPropertyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model implements IdContract, NameContract
{
    use HasFactory;
    use HasIdTrait;
    use HasNameTrait;
    use HasPriceAndCurrencyTrait;
    use HasPriceObjectPropertyTrait;

    public const TABLE = 'options';
    public const CUSTOMIZATION = 'customization_id';
    public const AMOUNT = 'amount';
    public const PRICE = 'price';
    public const CURRENCY = 'currency_id';

    protected $table = self::TABLE;
    protected $primaryKey = self::ID;

    protected function getPriceName(): string
    {
        return self::PRICE;
    }

    protected function getCurrencyName(): string
    {
        return self::CURRENCY;
    }

    public function customization(): BelongsTo
    {
        return $this->belongsTo(Customization::class);
    }
}
