<?php

if (!function_exists('createAbility')) {
    function createAbility(
        array $fields = [],
        int $count = null,
    ): App\Models\Ability {
        $factory = \App\Models\Ability::factory();

        if ($count) {
            $factory->count($count);
        }

        return $factory->create($fields);
    }
}

if (!function_exists('createRole')) {
    function createRole(
        array $fields = [],
        int $count = null,
    ): App\Models\Role {
        $factory = \App\Models\Role::factory();

        if ($count) {
            $factory->count($count);
        }

        return $factory->create($fields);
    }
}

if (!function_exists('createCurrency')) {
    function createCurrency(
        array $fields = [],
        int $count = null,
    ): App\Models\Currency {
        $factory = \App\Models\Currency::factory();

        if ($count) {
            $factory->count($count);
        }

        return $factory->create($fields);
    }
}

if (!function_exists('createUser')) {
    function createUser(
        array $fields = [],
        int $count = null,
    ): App\Models\User {
        $factory = \App\Models\User::factory();

        if ($count) {
            $factory->count($count);
        }

        return $factory->create($fields);
    }
}
