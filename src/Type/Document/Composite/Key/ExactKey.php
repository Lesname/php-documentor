<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document\Composite\Key;

/**
 * @psalm-immutable
 */
final class ExactKey implements Key
{
    public function __construct(public readonly string $value)
    {}

    public function matches(string $value): bool
    {
        return $this->value === $value;
    }
}
