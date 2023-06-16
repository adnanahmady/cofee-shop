<?php

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
