<?php

if (!function_exists('createCurrency')) {
    function createCurrency(
        int $count = null,
        array $fields = []
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
        int $count = null,
        array $fields = []
    ): App\Models\User {
        $factory = \App\Models\User::factory();

        if ($count) {
            $factory->count($count);
        }

        return $factory->create($fields);
    }
}
