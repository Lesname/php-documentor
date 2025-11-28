<?php

declare(strict_types=1);

namespace LesDocumentor\Type\Document\Composite\Key;

use Override;

/**
 * @psalm-immutable
 */
final class RegexKey implements Key
{
    public function __construct(public readonly string $pattern)
    {}

    #[Override]
    public function matches(string $value): bool
    {
        return preg_match("/{$this->pattern}/", $value) === 1;
    }
}
