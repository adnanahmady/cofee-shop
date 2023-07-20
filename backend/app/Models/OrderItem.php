<?php

namespace App\Models;

use App\Contracts\Models\Fields\IdContract;
use App\Traits\Models\HasPriceAndCurrencyTrait;
use App\Traits\Models\HasPriceObjectPropertyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model implements IdContract
{
    use HasFactory;
    use HasPriceAndCurrencyTrait;
    use HasPriceObjectPropertyTrait;

    public const TABLE = 'order_items';
    public const PRODUCT = 'product_id';
    public const ORDER = 'order_id';
    public const AMOUNT = 'amount';
    public const PRICE = 'price';
    public const CURRENCY = 'currency';

    protected $table = self::TABLE;

    protected $primaryKey = self::ID;

    protected $fillable = [
        self::PRODUCT,
        self::ORDER,
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

    public function getProductId(): int
    {
        return $this->{self::PRODUCT};
    }

    public function getOrderId(): int
    {
        return $this->{self::ORDER};
    }

    public function getAmount(): int
    {
        return $this->{self::AMOUNT};
    }

    public function setAmount(int $amount): void
    {
        $this->{self::AMOUNT} = $amount;
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
