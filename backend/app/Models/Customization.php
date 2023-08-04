<?php

namespace App\Models;

use App\Contracts\Models\Fields\IdContract;
use App\Contracts\Models\Fields\NameContract;
use App\Traits\Models\Fields\HasIdTrait;
use App\Traits\Models\Fields\HasNameTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customization extends Model implements IdContract, NameContract
{
    use HasFactory;
    use HasIdTrait;
    use HasNameTrait;

    public const TABLE = 'customizations';

    protected $table = self::TABLE;
    protected $primaryKey = self::ID;

    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }
}
