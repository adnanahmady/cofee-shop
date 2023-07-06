<?php

namespace App\Models;

use App\Interfaces\IdInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model implements IdInterface
{
    use HasFactory;

    public const TABLE = 'order_statuses';
    public const ID = 'id';
    public const NAME = 'name';

    protected $table = self::TABLE;
    protected $primaryKey = self::ID;

    protected $fillable = [
        self::NAME,
    ];

    public function getId(): int
    {
        return $this->{self::ID};
    }

    public function getName(): string
    {
        return $this->{self::NAME};
    }
}
