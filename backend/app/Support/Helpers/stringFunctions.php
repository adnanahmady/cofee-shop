<?php

if (!function_exists('escapeString')) {
    function escapeString(string $string, array $escapable = []): string
    {
        $newString = '';

        if (empty($escapable)) {
            $escapable = ['/'];
        }

        foreach ($escapable as $character) {
            $newString = str_replace(
                $character,
                '\\' . $character,
                $string
            );
        }

        return $newString;
    }
}

if (!function_exists('renderString')) {
    function renderString(string $string, array $params): string
    {
        foreach ($params as $param => $value) {
            $string = str_replace(
                "{{$param}}",
                $value,
                $string
            );
        }

        return $string;
    }
}
