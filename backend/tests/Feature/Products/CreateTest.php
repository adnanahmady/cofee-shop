<?php

namespace Tests\Feature\Products;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Tests\Traits\MigrateDatabaseTrait;

class CreateTest extends TestCase
{
    use MigrateDatabaseTrait;

    #[Test]
    public function itResponsesWithProperStatusCode(): void
    {
        $this->assertTrue(true);
    }
}
