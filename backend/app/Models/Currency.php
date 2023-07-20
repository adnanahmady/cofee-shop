<?php

namespace App\Models;

use App\Contracts\Models\Fields\IdContract;
use App\Contracts\Models\Fields\NameContract;
use App\Traits\Models\Fields\HasNameTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model implements
    IdContract,
    NameContract
{
    use HasFactory;
    use HasNameTrait;

    public const TABLE = 'currencies';
    public const CODE = 'code';
    public const DECIMAL_PLACES = 'decimal_places';

    protected $table = self::TABLE;

    protected $primaryKey = self::ID;

    protected $fillable = [
        self::NAME,
        self::CODE,
        self::DECIMAL_PLACES,
    ];

    public function getId(): int
    {
        return $this->{self::ID};
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
