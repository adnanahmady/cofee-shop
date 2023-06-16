<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Traits\AssertionsTrait;
use Tests\Traits\MigrateDatabaseTrait;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use AssertionsTrait;

    protected function setUpTraits(): void
    {
        $uses = parent::setUpTraits();

        if (isset($uses[MigrateDatabaseTrait::class])) {
            $this->migrateDatabaseSetUp();
        }
    }

    protected function tearDown(): void
    {
        $uses = $this->getTraits();

        if (isset($uses[MigrateDatabaseTrait::class])) {
            $this->migrateDatabaseTearDown();
        }
        parent::tearDown();
    }

    protected function getTraits(): array
    {
        return class_uses_recursive(static::class);
    }
}
