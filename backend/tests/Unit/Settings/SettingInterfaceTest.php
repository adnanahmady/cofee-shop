<?php

namespace Tests\Unit\Settings;

use App\Interfaces\SettingInterface;
use Tests\TestCase;

class SettingInterfaceTest extends TestCase
{
    // phpcs:ignore
    public function test_its_instances_should_be_able_to_present_themself_as_string(): void
    {
        $ref = new \ReflectionClass(SettingInterface::class);
        $toStringFunctionality = $ref->hasMethod('__toString');

        $this->assertTrue($toStringFunctionality);
    }
}
