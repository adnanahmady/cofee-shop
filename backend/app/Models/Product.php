<?php

namespace App\Models;

use App\ValueObjects\Products\PriceInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public const TABLE = 'products';
    public const ID = 'id';
    public const NAME = 'name';
    public const PRICE = 'price';
    public const CURRENCY = 'currency_id';

    protected $table = self::TABLE;

    protected $fillable = [
        self::NAME,
        self::PRICE,
        self::CURRENCY,
    ];

    private PriceInterface $priceObject;

    public function getId(): int
    {
        return $this->{self::ID};
    }

    public function getName(): string
    {
        return $this->{self::NAME};
    }

    public function setName(string $name): void
    {
        $this->{self::NAME} = $name;
    }

    public function getPriceObject(): PriceInterface
    {
        return $this->priceObject;
    }

    public function setPriceObject(PriceInterface $priceObject): self
    {
        $this->priceObject = $priceObject;
        $this->{self::PRICE} = $priceObject->getPrice();
        $this->{self::CURRENCY} = $priceObject->getCurrency()->getId();

        return $this;
    }
}
