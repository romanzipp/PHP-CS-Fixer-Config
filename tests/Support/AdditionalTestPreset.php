<?php

namespace romanzipp\Fixer\Tests\Support;

use romanzipp\Fixer\Presets\AbstractPreset;

class AdditionalTestPreset extends AbstractPreset
{
    public function getRules(): array
    {
        return [
            'additional_rule',
        ];
    }

    protected function getFilePatterns(): array
    {
        return [];
    }

    protected function getExcludedDirectories(): array
    {
        return [];
    }

    protected function getExcludedFiles(): array
    {
        return [];
    }
}
