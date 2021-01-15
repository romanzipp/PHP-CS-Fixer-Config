<?php

namespace romanzipp\Fixer\Tests\Support;

use romanzipp\Fixer\Presets\AbstractPreset;

class TestPreset extends AbstractPreset
{
    public function getRules(): array
    {
        return [
            'example_rule',
        ];
    }

    protected function getFilePatterns(): array
    {
        return [];
    }

    protected function getExcludedDirectories(): array
    {
        return [
            'ExcludedFolder',
        ];
    }

    protected function getExcludedFiles(): array
    {
        return [
            'excluded.php',
            '*.ignoreme.php',
        ];
    }
}
