<?php

namespace App\Repositories;

use App\Enums\AbilityEnum;
use App\Models\Ability;

class AbilityRepository
{
    public function firstOrCreate(string $slug, string $name): Ability
    {
        /* @var Ability */
        return Ability::query()->firstOrCreate(
            [Ability::SLUG => $slug],
            [Ability::NAME => $name]
        );
    }

    public function findAbility(AbilityEnum $ability): Ability
    {
        return $this->firstOrCreate(
            slug: $ability->slugify(),
            name: $ability->value
        );
    }
}
