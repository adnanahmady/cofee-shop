<?php

namespace App\Models;

use App\Contracts\Models\Fields\IdContract;
use App\Contracts\Models\Fields\NameContract;
use App\Contracts\Models\Fields\SlugContract;
use App\Contracts\Models\RoleAbilityContract;
use App\Traits\Models\Fields\HasIdTrait;
use App\Traits\Models\Fields\HasNameTrait;
use App\Traits\Models\Fields\HasSlugTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model implements
    IdContract,
    NameContract,
    SlugContract
{
    use HasFactory;
    use HasIdTrait;
    use HasNameTrait;
    use HasSlugTrait;

    public const TABLE = 'roles';

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

    public function abilities(): BelongsToMany
    {
        return $this->belongsToMany(
            Ability::class,
            RoleAbilityContract::TABLE
        )->withTimestamps();
    }
}
