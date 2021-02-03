<?php

namespace romanzipp\Fixer\Tests;

use romanzipp\Fixer\Config;
use romanzipp\Fixer\Tests\Support\TestPreset;

class DynamicPresetTest extends TestCase
{
    public function testDynamicPrestUpdatesFinder()
    {
        $config = Config::make()
            ->in(__DIR__ . '/Files')
            ->preset(new TestPreset())
            ->exclude('partially-excluded.php')
            ->excludeDirectories('PartiallyExcludedFolder')
            ->out();

        $files = iterator_to_array($config->getFinder()->getIterator());

        self::assertArrayHasKey(__DIR__ . '/Files/included.php', $files);

        self::assertArrayNotHasKey(__DIR__ . '/Files/partially-excluded.php', $files);
        self::assertArrayNotHasKey(__DIR__ . '/Files/excluded.php', $files);
        self::assertArrayNotHasKey(__DIR__ . '/Files/foo.ignoreme.php', $files);

        self::assertArrayHasKey(__DIR__ . '/Files/IncludedFolder/file.php', $files);

        self::assertArrayNotHasKey(__DIR__ . '/Files/ExcludedFolder/file.php', $files);
        self::assertArrayNotHasKey(__DIR__ . '/Files/PartiallyExcludedFolder/file.php', $files);
    }

    public function testRules()
    {
        $builder = Config::make()
            ->in(__DIR__ . '/Files')
            ->withPreset(new TestPreset());

        self::assertSame(['example_rule'], $builder->out()->getRules());

        $builder->withRules(['additional_rule']);

        self::assertContains('example_rule', $builder->out()->getRules());
        self::assertContains('additional_rule', $builder->out()->getRules());
    }
}
