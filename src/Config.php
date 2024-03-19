<?php

namespace romanzipp\Fixer;

use PhpCsFixer\Config as BaseConfig;
use PhpCsFixer\Finder;
use romanzipp\Fixer\Fixers\PhpDocConvertClassesToFqcn;
use romanzipp\Fixer\Presets\AbstractPreset;
use romanzipp\Fixer\Presets\DynamicPreset;

final class Config
{
    /**
     * @var BaseConfig
     */
    public $config;

    /**
     * @var Finder
     */
    public $finder;

    /**
     * @var string|null
     */
    private $workingDir;

    /**
     * @var \romanzipp\Fixer\Presets\AbstractPreset[]
     */
    private $presets;

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
     * @param AbstractPreset $preset
     *
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
     * @param AbstractPreset $preset
     *
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
     *
     * @return $this
     */
    public function in(string $workingDir): self
    {
        $this->finder->in(
            $this->workingDir = $workingDir
        );

        return $this;
    }

    /**
     * Allow risky rules.
     *
     * @param bool $allow
     *
     * @return $this
     */
    public function allowRisky(bool $allow = true): self
    {
        $this->config->setRiskyAllowed($allow);

        return $this;
    }

    /**
     * Add additional rules. Please rather use presets. Nonetheless, here you go.
     *
     * @param array $rules
     *
     * @return $this
     */
    public function withRules(array $rules): self
    {
        $this->updateDynamicPreset(static function (DynamicPreset $preset) use ($rules) {
            $preset->rules = array_merge($preset->rules, $rules);
        });

        return $this;
    }

    /**
     * Add single or many files to the list of excluded files.
     *
     * @param array|string $files
     *
     * @return self
     */
    public function exclude($files): self
    {
        $this->updateDynamicPreset(static function (DynamicPreset $preset) use ($files) {
            $preset->excludedFiles = array_merge($preset->excludedFiles, (array) $files);
        });

        return $this;
    }

    /**
     * Add single or many directories to the list of excluded directories.
     *
     * @param array|string $directories
     *
     * @return self
     */
    public function excludeDirectories($directories): self
    {
        $this->updateDynamicPreset(static function (DynamicPreset $preset) use ($directories) {
            $preset->excludedDirectories = array_merge($preset->excludedDirectories, (array) $directories);
        });

        return $this;
    }

    /**
     * Add a callback function to modify the php-cs-fixer config instance.
     *
     * @param \Closure $callback
     *
     * @return $this
     */
    public function configCallback(\Closure $callback): self
    {
        $callback($this->config);

        return $this;
    }

    /**
     * Add a callback function to modify the php-cs-fixer finder instance.
     *
     * @param \Closure $callback
     *
     * @return $this
     */
    public function finderCallback(\Closure $callback): self
    {
        $callback($this->finder);

        return $this;
    }

    /**
     * Run a given callback on the dynamic preset.
     *
     * @param \Closure $callback
     */
    private function updateDynamicPreset(\Closure $callback): void
    {
        array_walk($this->presets, static function (AbstractPreset $preset) use ($callback) {
            if ($preset instanceof DynamicPreset) {
                $callback($preset);
            }
        });
    }

    /**
     * Generate the php-cs-fixer config for final return.
     *
     * @return BaseConfig
     */
    public function out(): BaseConfig
    {
        if (null === $this->workingDir) {
            throw new \RuntimeException('The working dir has not been set. Please specify the `in()` method');
        }

        // Note: We don't need to merge all finder configuration values since this
        // is already done internally by the symfony finder instance.
        foreach ($this->presets as $preset) {
            $preset->populateFinder($this->finder);
        }

        $this->config->registerCustomFixers([
            new PhpDocConvertClassesToFqcn(),
        ]);

        $rules = [];

        $presets = array_reverse($this->presets);

        array_walk($presets, static function (AbstractPreset $preset) use (&$rules) {
            $rules = array_merge($rules, $preset->getRules());
        }, $this->presets);

        $this->config->setRules($rules);
        $this->config->setFinder($this->finder);

        return $this->config;
    }
}
