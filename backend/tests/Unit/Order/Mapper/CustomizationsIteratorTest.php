<?php

namespace Tests\Unit\Order\Mapper;

use App\Exceptions\MissingOffsetException;
use App\Support\RequestMappers\Orders\CustomizationsIterator;
use Tests\TestCase;

class CustomizationsIteratorTest extends TestCase
{
    // phpcs:ignore
    public function test_given_customization_items_should_contain_expected_fields(): void
    {
        $this->withoutExceptionHandling();
        $this->expectException(MissingOffsetException::class);

        $iterator = new CustomizationsIterator([[]]);

        $iterator->current();
    }
}
