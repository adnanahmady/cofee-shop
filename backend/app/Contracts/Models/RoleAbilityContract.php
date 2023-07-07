<?php

namespace App\Contracts\Models;

interface RoleAbilityContract
{
    public const TABLE = 'role_ability';
    public const ID = 'id';
    public const ROLE = 'role_id';
    public const ABILITY = 'ability_id';
}
