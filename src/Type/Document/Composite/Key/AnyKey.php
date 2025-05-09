<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document\Composite\Key;

/**
 * @psalm-immutable
 */
final class AnyKey implements Key
{
    public function matches(string $value): bool
    {
        return true;
    }
}
