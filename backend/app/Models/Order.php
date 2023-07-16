<?php

namespace App\Models;

use App\Interfaces\IdInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Order extends Model
{
    use HasFactory;

    public const TABLE = 'orders';
    public const ID = 'id';
    public const USER = 'user_id';
    public const STATUS = 'status_id';

    protected $table = self::TABLE;

    protected $primaryKey = self::ID;

    protected $fillable = [
        self::USER,
    ];

    public function getId(): int
    {
        return $this->{self::ID};
    }

    public function getUserId(): int
    {
        return $this->{self::USER};
    }

    public function setUser(User $user): void
    {
        $this->{self::USER} = $user->getId();
    }

    public function setStatus(IdInterface $status): void
    {
        $this->{self::STATUS} = $status->getId();
    }

    public function getCreatedAt(): string
    {
        return $this->{self::CREATED_AT};
    }

    public function getUpdatedAt(): string
    {
        return $this->{self::UPDATED_AT};
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function itemProduct(): HasOneThrough
    {
        return $this->hasOneThrough(Product::class, OrderItem::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(
            OrderStatus::class,
            self::STATUS,
            OrderStatus::ID
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
