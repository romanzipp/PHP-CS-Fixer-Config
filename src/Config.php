<?php

namespace romanzipp\Fixer;

use PhpCsFixer\Config as BaseConfig;
use PhpCsFixer\Finder;
use romanzipp\Fixer\Presets\AbstractPreset;
use RuntimeException;

final class Config
{
    /**
     * @var \PhpCsFixer\Config
     */
    public $config;

    /**
     * @var \PhpCsFixer\Finder
     */
    public $finder;

    /**
     * @var string|null
     */
    private $workingDir = null;

    /**
     * @var \romanzipp\Fixer\Presets\AbstractPreset[]
     */
    private $presets;

    public function __construct()
    {
        $this->config = new BaseConfig();
        $this->finder = new Finder();
    }

    public static function make(): self
    {
        return new self();
    }

    public function preset(AbstractPreset $preset): self
    {
        $this->presets = [$preset];

        return $this;
    }

    public function withPreset(AbstractPreset $preset): self
    {
        $this->presets[] = $preset;

        return $this;
    }

    public function in(string $workingDir): self
    {
        $this->workingDir = $workingDir;

        return $this;
    }

    public function out(): BaseConfig
    {
        if (null === $this->workingDir) {
            throw new RuntimeException('The working dir has not been set');
        }

        $this->finder->in($this->workingDir);

        // Note: We don't need to merge all finder configuration values since this
        // is already done internally by the symfony finder instance.
        foreach ($this->presets as $preset) {
            $preset->populateFinder($this->finder);
        }

        $rules = [];

        array_walk($this->presets, static function (AbstractPreset $preset) use (&$rules) {
            $rules = array_merge($rules, $preset->getRules());
        }, $this->presets);

        $this->config->setRules($rules);
        $this->config->setFinder($this->finder);

        return $this->config;
    }
}
