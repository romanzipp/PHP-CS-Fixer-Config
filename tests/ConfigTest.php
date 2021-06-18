<?php

namespace romanzipp\Fixer\Tests;

use PhpCsFixer\Finder;
use romanzipp\Fixer\Config;
use romanzipp\Fixer\Tests\Support\AdditionalTestPreset;
use romanzipp\Fixer\Tests\Support\TestPreset;

class ConfigTest extends TestCase
{
    public function testRulesAreSet()
    {
        $config = Config::make()
            ->in(__DIR__ . '/files/dummy')
            ->preset(new TestPreset())
            ->out();

        self::assertSame(['example_rule'], $config->getRules());
    }

    public function testFinderConfiguredCorrectly()
    {
        $config = Config::make()
            ->in(__DIR__ . '/files/dummy')
            ->preset(new TestPreset())
            ->out();

        $files = iterator_to_array($config->getFinder()->getIterator());

        self::assertArrayHasKey(__DIR__ . '/files/dummy/included.php', $files);
        self::assertArrayHasKey(__DIR__ . '/files/dummy/partially-excluded.php', $files);

        self::assertArrayNotHasKey(__DIR__ . '/files/dummy/excluded.php', $files);
        self::assertArrayNotHasKey(__DIR__ . '/files/dummy/foo.ignoreme.php', $files);

        self::assertArrayHasKey(__DIR__ . '/files/dummy/IncludedFolder/file.php', $files);
        self::assertArrayHasKey(__DIR__ . '/files/dummy/PartiallyExcludedFolder/file.php', $files);

        self::assertArrayNotHasKey(__DIR__ . '/files/dummy/ExcludedFolder/file.php', $files);
    }

    public function testMultiplePresetsExtendRules()
    {
        $config = Config::make()
            ->in(__DIR__ . '/files/dummy')
            ->withPreset(new TestPreset())
            ->withPreset(new AdditionalTestPreset())
            ->out();

        self::assertContains('example_rule', $config->getRules());
        self::assertContains('additional_rule', $config->getRules());

        self::assertSame(['example_rule', 'additional_rule'], $config->getRules());
    }

    public function testConfigCallbackMethodOnFinder()
    {
        $config = Config::make()
            ->in(__DIR__ . '/files/dummy')
            ->preset(new TestPreset())
            ->finderCallback(function (Finder $finder): void {
                $finder->notName('partially-excluded.php');
            })
            ->out();

        $files = iterator_to_array($config->getFinder()->getIterator());

        self::assertArrayHasKey(__DIR__ . '/files/dummy/included.php', $files);

        self::assertArrayNotHasKey(__DIR__ . '/files/dummy/partially-excluded.php', $files);
        self::assertArrayNotHasKey(__DIR__ . '/files/dummy/excluded.php', $files);
        self::assertArrayNotHasKey(__DIR__ . '/files/dummy/foo.ignoreme.php', $files);

        self::assertArrayHasKey(__DIR__ . '/files/dummy/IncludedFolder/file.php', $files);
        self::assertArrayHasKey(__DIR__ . '/files/dummy/PartiallyExcludedFolder/file.php', $files);

        self::assertArrayNotHasKey(__DIR__ . '/files/dummy/ExcludedFolder/file.php', $files);
    }

    public function testRiskyRules()
    {
        $config = Config::make();

        self::assertFalse($config->config->getRiskyAllowed());

        $config->allowRisky();

        self::assertTrue($config->config->getRiskyAllowed());
    }
}
