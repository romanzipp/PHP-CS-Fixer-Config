<?php

namespace romanzipp\Fixer\Fixers;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Analyzer\NamespacesAnalyzer;
use PhpCsFixer\Tokenizer\Analyzer\NamespaceUsesAnalyzer;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use romanzipp\Fixer\Fixers\Support\UsesDeclaration;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

final class PhpDocConvertClassesToFqcn implements FixerInterface
{
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'PHPDoc annotations must only include fully qualified class names',
            [new CodeSample('<?php
use App\Foo;
use App\Bar;

/**
 * @param  Foo $foo
 * @return Bar[]  
 */
function foo(Foo $foo): array {}
?>')]
        );
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isAnyTokenKindsFound([T_DOC_COMMENT]);
    }

    public function isRisky(): bool
    {
        return false;
    }

    public function getName(): string
    {
        return 'RomanZipp/phpdoc_fqcn';
    }

    public function getPriority(): int
    {
        return 0;
    }

    public function supports(SplFileInfo $file): bool
    {
        return true;
    }

    public function fix(SplFileInfo $file, Tokens $tokens): void
    {
        /** @var \romanzipp\Fixer\Fixers\Support\UsesDeclaration[] $uses */
        $uses = [];

        // Get `uses` declarations from current file
        foreach ((new NamespaceUsesAnalyzer())->getDeclarationsFromTokens($tokens) as $use) {
            $uses[] = new UsesDeclaration($use->getShortName(), $use->getFullName());
        }

        $namespaces = (new NamespacesAnalyzer())->getDeclarations($tokens);

        // Locate all classes in folder
        foreach ($namespaces as $namespace) {
            $finder = new Finder();
            foreach ($finder->in($file->getPath()) as $item) {
                if ('php' !== $item->getExtension()) {
                    continue;
                }

                $startChar = $item->getFilename()[0];
                if ( ! ctype_upper($startChar)) {
                    continue;
                }

                $uses[] = new UsesDeclaration(
                    $className = str_replace('.php', '', $item->getFilename()),
                    $namespace->getFullName() . '\\' . $className
                );
            }
        }

        for ($index = 0; $index < $tokens->count(); ++$index) {
            /** @var \PhpCsFixer\Tokenizer\Token $token */
            $token = $tokens[$index];

            if ( ! $token->isGivenKind(T_DOC_COMMENT)) {
                continue;
            }

            $match = false;
            $newContent = preg_replace_callback('/@(?<tag>param|return) (?<class>[A-Za-z0-9_\\\\]+)(?<arr>\[\])? ?\$?(?<var>[A-Za-z0-9_]+)?/m', function (array $matches) use (&$match, $uses) {
                $original = $matches[0];
                $matches = array_filter($matches, function ($index) {
                    return ! is_int($index);
                }, ARRAY_FILTER_USE_KEY);

                if ( ! $matches['class']) {
                    return $original;
                }

                if (str_starts_with($matches['class'], '\\')) {
                    return $original;
                }

                $match = true;

                $foundUse = null;
                foreach ($uses as $use) {
                    if ($use->short === $matches['class']) {
                        $matches['class'] = $use->full;
                        $foundUse = true;
                        break;
                    }
                }

                if ( ! $foundUse) {
                    return $original;
                }

                $docLine = '@' . $matches['tag'] . ' \\' . $matches['class'];
                if (isset($matches['arr']) && ! empty($matches['arr'])) {
                    $docLine .= '[]';
                }
                if (isset($matches['var']) && ! empty($matches['var'])) {
                    $docLine .= ' $' . $matches['var'];
                }

                return $docLine;
            }, $token->getContent());

            if ($match) {
                $tokens[$index] = new Token([T_DOC_COMMENT, $newContent]);
            }
        }
    }
}
