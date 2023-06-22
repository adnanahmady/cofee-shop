<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    public const TABLE = 'currencies';
    public const ID = 'id';
    public const NAME = 'name';
    public const CODE = 'code';
    public const DECIMAL_PLACES = 'decimal_places';

    protected $table = self::TABLE;

    protected $fillable = [
        self::NAME,
        self::CODE,
        self::DECIMAL_PLACES,
    ];

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

    public function getCode(): string
    {
        return $this->{self::CODE};
    }

    public function setCode(string $code): void
    {
        $this->{self::CODE} = $code;
    }

    public function getDecimalPlaces(): int
    {
        return $this->{self::DECIMAL_PLACES} ?? 0;
    }

    public function setDecimalPlaces(int $decimalPlaces): self
    {
        $this->{self::DECIMAL_PLACES} = $decimalPlaces;

        return $this;
    }
}
