<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document;

/**
 * @psalm-immutable
 */
final class ReferenceTypeDocument extends AbstractTypeDocument
{
    public function __construct(string $reference, ?string $description = null, ?string $deprecated = null)
    {
        parent::__construct($reference, $description, $deprecated);
    }
}
