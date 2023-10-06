<?php

namespace Tests\Unit\Settings;

use App\Settings\KeyResolver;
use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class KeyResolverTest extends TestCase
{
    #[TestWith(['dummy*setting+2', 'dummy.setting.2'])]
    #[TestWith(['dummy[setting]2', 'dummy.setting.2'])]
    #[TestWith(['dummy^setting$2', 'dummy.setting.2'])]
    #[TestWith(['dummy\'setting"2', 'dummy.setting.2'])]
    #[TestWith(['dummy\setting/2', 'dummy.setting.2'])]
    #[TestWith(['dummy(setting)2', 'dummy.setting.2'])]
    #[TestWith(['dummy#setting.2', 'dummy.setting.2'])]
    #[TestWith(['dummy-setting_2', 'dummy.setting.2'])]
    #[TestWith(['dummy_setting_2', 'dummy.setting.2'])]
    #[TestWith(['dummy_setting', 'dummy.setting'])]
    public function test_it_should_convert_given_key_to_excepting_form(
        string $key,
        string $expectation
    ): void {
        $resolver = new KeyResolver(key: $key);

        $resolvedKey = $resolver->resolve();

        $this->assertSame($expectation, $resolvedKey);
    }
}
