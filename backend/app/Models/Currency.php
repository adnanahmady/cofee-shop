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

    protected $table = self::TABLE;

    protected $fillable = [
        self::NAME,
        self::CODE,
    ];

    public function getId(): int
    {
        return $this->{self::ID};
    }

    public function getName(): string
    {
        return $this->{self::NAME};
    }

    public function getCode(): string
    {
        return $this->{self::CODE};
    }
}
