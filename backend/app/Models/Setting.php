<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public const TABLE = 'settings';
    public const KEY = 'key';
    public const VALUE = 'value';

    protected $primaryKey = self::KEY;
    protected $table = self::TABLE;
    protected $keyType = 'string';
    public $incrementing = false;

    public function getKey(): string
    {
        return $this->{self::KEY};
    }

    public function getValue(): string
    {
        return $this->{self::VALUE};
    }

    public function setKey(string $key): void
    {
        $this->{self::KEY} = $key;
    }

    public function setValue(string $value): void
    {
        $this->{self::VALUE} = $value;
    }
}
