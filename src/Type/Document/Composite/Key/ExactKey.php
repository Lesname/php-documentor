<?php

declare(strict_types=1);

namespace LesDocumentor\Type\Document\Composite\Key;

use Override;

/**
 * @psalm-immutable
 */
final class ExactKey implements Key
{
    public function __construct(public readonly string $value)
    {}

    #[Override]
    public function matches(string $value): bool
    {
        return $this->value === $value;
    }
}
