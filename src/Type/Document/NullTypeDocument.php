<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document;

use Override;

/**
 * @psalm-immutable
 */
final class NullTypeDocument extends AbstractTypeDocument
{
    #[Override]
    public function isNullable(): bool
    {
        return true;
    }
}
