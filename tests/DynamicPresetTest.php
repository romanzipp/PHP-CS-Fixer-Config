<?php

namespace romanzipp\Fixer\Tests;

use romanzipp\Fixer\Config;
use romanzipp\Fixer\Tests\Support\TestPreset;

class DynamicPresetTest extends TestCase
{
    public function testDynamicPrestUpdatesFinder()
    {
        $config = Config::make()
            ->in(__DIR__ . '/files/dummy')
            ->preset(new TestPreset())
            ->exclude('partially-excluded.php')
            ->excludeDirectories('PartiallyExcludedFolder')
            ->out();

        $files = iterator_to_array($config->getFinder()->getIterator());

        self::assertArrayHasKey(__DIR__ . '/files/dummy/included.php', $files);

        self::assertArrayNotHasKey(__DIR__ . '/files/dummy/partially-excluded.php', $files);
        self::assertArrayNotHasKey(__DIR__ . '/files/dummy/excluded.php', $files);
        self::assertArrayNotHasKey(__DIR__ . '/files/dummy/foo.ignoreme.php', $files);

        self::assertArrayHasKey(__DIR__ . '/files/dummy/IncludedFolder/file.php', $files);

        self::assertArrayNotHasKey(__DIR__ . '/files/dummy/ExcludedFolder/file.php', $files);
        self::assertArrayNotHasKey(__DIR__ . '/files/dummy/PartiallyExcludedFolder/file.php', $files);
    }

    public function testRules()
    {
        $builder = Config::make()
            ->in(__DIR__ . '/files/dummy')
            ->withPreset(new TestPreset());

        self::assertSame(['example_rule'], $builder->out()->getRules());

        $builder->withRules(['additional_rule']);

        self::assertContains('example_rule', $builder->out()->getRules());
        self::assertContains('additional_rule', $builder->out()->getRules());
    }
}
