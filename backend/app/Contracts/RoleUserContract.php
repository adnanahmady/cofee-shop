<?php

namespace App\Contracts;

interface RoleUserContract
{
    public const TABLE = 'role_user';
    public const ID = 'id';
    public const ROLE = 'role_id';
    public const USER = 'user_id';
}
