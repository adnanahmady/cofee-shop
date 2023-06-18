<?php

namespace App\Loaders\Roles;

interface AbilityLoaderInterface
{
    public function getAbilities(string $default): array;
}
