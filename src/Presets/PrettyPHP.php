<?php

namespace romanzipp\Fixer\Presets;

class PrettyPHP extends AbstractPreset
{
    public function getRules(): array
    {
        return [
            '@Symfony' => true,                         // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/2.17/doc/ruleSets/Symfony.rst
            'array_indentation' => true,                // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/2.17/doc/rules/whitespace/array_indentation.rst
            'braces' => true,                           // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/2.17/doc/rules/basic/braces.rst
                                                        // see https://github.com/FriendsOfPHP/PHP-CS-Fixer/issues/4487
            'concat_space' => [                         // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/2.17/doc/rules/operator/concat_space.rst
                'spacing' => 'one',
            ],
            'no_superfluous_phpdoc_tags' => false,      // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/2.17/doc/rules/phpdoc/no_superfluous_phpdoc_tags.rst
            'no_useless_return' => true,                // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/2.17/doc/rules/return_notation/no_useless_return.rst
            'not_operator_with_space' => true,          // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/2.17/doc/rules/operator/not_operator_with_space.rst
            'phpdoc_align' => [                         // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/2.17/doc/rules/phpdoc/phpdoc_align.rst
                'align' => 'left',
            ],
            'phpdoc_return_self_reference' => [         // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/2.17/doc/rules/phpdoc/phpdoc_return_self_reference.rst
                'this' => 'self',
                '@this' => 'self',
                '$self' => 'self',
                '@self' => 'self',
                '$static' => 'static',
                '@static' => 'static',
            ],
            'phpdoc_order' => true,                     // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/2.17/doc/rules/phpdoc/phpdoc_order.rst
            'phpdoc_to_comment' => false,               // https://github.com/FriendsOfPHP/PHP-CS-Fixer/blob/2.17/doc/rules/phpdoc/phpdoc_to_comment.rst
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
