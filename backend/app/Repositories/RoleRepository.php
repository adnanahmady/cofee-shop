<?php

namespace App\Repositories;

use App\Models\Ability;
use App\Models\Role;
use App\Support\Types\AbilitiesType;

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

    public function firstOrCreate(string $slug, string $name): Role
    {
        /* @var Role */
        return Role::query()->firstOrCreate(
            [Role::SLUG => $slug],
            [Role::NAME => $name]
        );
    }

    public function syncAbilities(Role $role, AbilitiesType $abilities): void
    {
        $role->abilities()->syncWithoutDetaching($abilities->slugify());
    }
}
