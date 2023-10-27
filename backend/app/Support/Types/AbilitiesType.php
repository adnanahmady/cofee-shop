<?php

namespace App\Support\Types;

use App\Enums\AbilityEnum;
use App\Repositories\AbilityRepository;

// phpcs:disable PSR1.Files.SideEffects
readonly class AbilitiesType
{
    /** @param array<AbilityEnum> $items */
    public function __construct(private array $items) {}

    public function slugify(): array
    {
        $repository = new AbilityRepository();

        return array_map(
            fn(AbilityEnum $a) => $repository->findAbility($a)->getId(),
            $this->items
        );
    }
}
