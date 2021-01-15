<?php

namespace romanzipp\Fixer\Presets;

final class DynamicPreset extends AbstractPreset
{
    /**
     * @var array
     */
    public $rules = [];

    /**
     * @var array
     */
    public $filePatterns = [];

    /**
     * @var array
     */
    public $excludedDirectories = [];

    /**
     * @var array
     */
    public $excludedFiles = [];

    public function getRules(): array
    {
        return $this->rules;
    }

    protected function getFilePatterns(): array
    {
        return $this->filePatterns;
    }

    protected function getExcludedDirectories(): array
    {
        return $this->excludedDirectories;
    }

    protected function getExcludedFiles(): array
    {
        return $this->excludedFiles;
    }
}
