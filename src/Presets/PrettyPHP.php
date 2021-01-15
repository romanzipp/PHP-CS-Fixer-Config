<?php

namespace romanzipp\Fixer\Presets;

class PrettyPHP extends AbstractPreset
{
    public function getRules(): array
    {
        return [
            '@Symfony' => true,
            'array_indentation' => true,
            'array_syntax' => [
                'syntax' => 'short',
            ],
            'binary_operator_spaces' => true,
            // https://github.com/FriendsOfPHP/PHP-CS-Fixer/issues/4487
            'braces' => true,
            'compact_nullable_typehint' => true,
            'concat_space' => [
                'spacing' => 'one',
            ],
            'fully_qualified_strict_types' => true,
            'no_superfluous_phpdoc_tags' => false,
            'no_useless_return' => true,
            'not_operator_with_space' => true,
            'ordered_imports' => [
                'sortAlgorithm' => 'alpha',
            ],
            'php_unit_fqcn_annotation' => true,
            'phpdoc_align' => [
                'align' => 'left',
            ],
            'phpdoc_order' => true,
            'phpdoc_scalar' => true,
            'phpdoc_to_comment' => false,
            'phpdoc_var_without_name' => true,
        ];
    }

    protected function getFilePatterns(): array
    {
        return [
            '*.php',
            '.php_cs',
            '.php_cs.dist',
        ];
    }

    protected function getExcludedDirectories(): array
    {
        return [
            'vendor',
        ];
    }

    protected function getExcludedFiles(): array
    {
        return [];
    }
}
