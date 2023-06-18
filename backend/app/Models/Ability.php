<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    use HasFactory;

    public const TABLE = 'abilities';
    public const ID = 'id';
    public const NAME = 'name';
    public const SLUG = 'slug';

    protected $table = self::TABLE;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        self::NAME,
        self::SLUG,
    ];

    public function getName(): string
    {
        return $this->{self::NAME};
    }

    public function getSlug(): string
    {
        return $this->{self::SLUG};
    }
}
