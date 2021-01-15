<?php

namespace romanzipp\Fixer\Presets;

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

abstract class AbstractPreset
{
    abstract public function getRules(): array;

    abstract protected function getFilePatterns(): array;

    abstract protected function getExcludedDirectories(): array;

    abstract protected function getExcludedFiles(): array;

    protected function ignoreDotFiles(): bool
    {
        return true;
    }

    protected function ignoreVcs(): bool
    {
        return true;
    }

    public function populateFinder(Finder $finder): void
    {
        $finder->exclude(
            $this->getExcludedDirectories()
        );

        $finder->name(
            $this->getFilePatterns()
        );

        $finder->notName(
            $this->getExcludedFiles()
        );

        $finder->ignoreDotFiles(
            $this->ignoreDotFiles()
        );

        $finder->ignoreVCS(
            $this->ignoreVcs()
        );
    }
}
