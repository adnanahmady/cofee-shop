<?php

namespace App\Support\Loaders\Roles;

interface AbilityLoaderInterface
{
    public function getAbilities(string $default): array;
}
