<?php

namespace App\Models;

use App\Contracts\Models\Fields\IdContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model implements IdContract
{
    use HasFactory;

    public const TABLE = 'order_statuses';
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
