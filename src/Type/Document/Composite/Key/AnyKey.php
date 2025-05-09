<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document\Composite\Key;

use Override;

/**
 * @psalm-immutable
 */
final class AnyKey implements Key
{
    #[Override]
    public function matches(string $value): bool
    {
        return true;
    }
}
