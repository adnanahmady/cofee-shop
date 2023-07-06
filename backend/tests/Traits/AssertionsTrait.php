<?php

namespace Tests\Traits;

trait AssertionsTrait
{
    protected function assertArrayHasKeys(
        array $keys,
        array $array,
        string $message = '',
    ): void {
        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $array, $message);
        }
    }

    protected function assertArrayNotHasKeys(
        array $keys,
        array $array,
        string $message = '',
    ): void {
        foreach ($keys as $key) {
            $this->assertArrayNotHasKey($key, $array, $message);
        }
    }
}
