<?php

namespace romanzipp\Fixer\Presets;

class PrettySymfony extends PrettyPHP
{
    public function getRules(): array
    {
        return parent::getRules();
    }

    protected function getFilePatterns(): array
    {
        return parent::getFilePatterns();
    }

    protected function getExcludedDirectories(): array
    {
        return [
            'var',
        ] + parent::getExcludedDirectories();
    }
}
