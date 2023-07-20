<?php

namespace App\Models;

use App\Contracts\Models\Fields\IdContract;
use App\Contracts\Models\Fields\NameContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model implements
    IdContract,
    NameContract
{
    use HasFactory;

    public const TABLE = 'abilities';
    public const SLUG = 'slug';

    protected $table = self::TABLE;

    protected $primaryKey = self::ID;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        self::NAME,
        self::SLUG,
    ];

    public function getId(): int
    {
        return $this->{self::ID};
    }

    public function getName(): string
    {
        return $this->{self::NAME};
    }

    public function getSlug(): string
    {
        return $this->{self::SLUG};
    }
}
