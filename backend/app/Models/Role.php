<?php

namespace App\Models;

use App\Contracts\RoleAbilityContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory;

    public const TABLE = 'roles';
    public const ID = 'id';
    public const NAME = 'name';
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

    public function getName(): string
    {
        return $this->{self::NAME};
    }

    public function getSlug(): string
    {
        return $this->{self::SLUG};
    }

    public function abilities(): BelongsToMany
    {
        return $this->belongsToMany(
            Ability::class,
            RoleAbilityContract::TABLE
        )->withTimestamps();
    }
}
