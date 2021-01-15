<?php

namespace romanzipp\Fixer;

use Closure;
use PhpCsFixer\Config as BaseConfig;
use PhpCsFixer\Finder;
use romanzipp\Fixer\Presets\AbstractPreset;
use romanzipp\Fixer\Presets\DynamicPreset;
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
    private $presets = [];

    public function __construct()
    {
        $this->config = new BaseConfig();
        $this->finder = new Finder();

        $this->presets = [
            new DynamicPreset(),
        ];
    }

    public static function make(): self
    {
        return new self();
    }

    /**
     * Set the preset.
     *
     * @param \romanzipp\Fixer\Presets\AbstractPreset $preset
     * @return $this
     */
    public function preset(AbstractPreset $preset): self
    {
        $this->presets = array_filter($this->presets, static function (AbstractPreset $preset) {
            return $preset instanceof DynamicPreset;
        });

        $this->withPreset($preset);

        return $this;
    }

    /**
     * Add a preset.
     *
     * @param \romanzipp\Fixer\Presets\AbstractPreset $preset
     * @return $this
     */
    public function withPreset(AbstractPreset $preset): self
    {
        $this->presets[] = $preset;

        return $this;
    }

    /**
     * Set the working directory.
     *
     * @param string $workingDir
     * @return $this
     */
    public function in(string $workingDir): self
    {
        $this->workingDir = $workingDir;

        return $this;
    }

    /**
     * Add single or many files to the list of excluded files.
     *
     * @param array|string $files
     * @return self
     */
    public function exclude($files): self
    {
        array_walk($this->presets,  function (AbstractPreset $preset) use ($files) {
            if ($preset instanceof DynamicPreset) {
                $preset->excludedFiles = array_merge($preset->excludedFiles, (array) $files);
            }
        });

        return $this;
    }

    /**
     * Add single or many files to the list of excluded files.
     *
     * @param array|string $directories
     * @return self
     */
    public function excludeDirectories($directories): self
    {
        array_walk($this->presets, static function (AbstractPreset $preset) use ($directories) {
            if ($preset instanceof DynamicPreset) {
                $preset->excludedDirectories = array_merge($preset->excludedDirectories, (array) $directories);
            }
        });

        return $this;
    }

    /**
     * Add a callback function to modify the php-cs-fixer config instance.
     *
     * @param \Closure $callback
     * @return $this
     */
    public function configCallback(Closure $callback): self
    {
        $callback($this->config);

        return $this;
    }

    /**
     * Add a callback function to modify the php-cs-fixer finder instance.
     *
     * @param \Closure $callback
     * @return $this
     */
    public function finderCallback(Closure $callback): self
    {
        $callback($this->finder);

        return $this;
    }

    /**
     * Generate the php-cs-fixer config for final return.
     *
     * @return \PhpCsFixer\Config
     */
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
