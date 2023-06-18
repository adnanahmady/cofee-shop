<?php

namespace App\Repositories;

use App\Models\Ability;
use App\Models\Role;

class RoleRepository
{
    public function findBySlug(string $slug): Role|null
    {
        return Role::where(Role::SLUG, $slug)->first();
    }

    public function getAbilities(Role $role): array|null
    {
        return $role->abilities
            ?->pluck(Ability::SLUG)
            ->toArray();
    }
}
