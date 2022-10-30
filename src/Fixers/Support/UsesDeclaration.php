<?php

namespace romanzipp\Fixer\Fixers\Support;

final class UsesDeclaration
{
    public string $short;
    public string $full;

    public function __construct(string $short, string $full)
    {
        $this->short = $short;
        $this->full = $full;
    }
}
