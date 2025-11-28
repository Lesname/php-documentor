<?php

declare(strict_types=1);

namespace LesDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
final class AnyTypeDocument extends AbstractTypeDocument
{
    public function __construct(
        ?string $reference = null,
        ?string $description = null,
    ) {
        parent::__construct($reference, $description);
    }
}
