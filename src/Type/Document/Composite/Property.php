<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document\Composite;

use LessDocumentor\Type\Document\TypeDocument;

/**
 * @psalm-immutable
 */
final class Property
{
    public function __construct(
        public readonly TypeDocument $type,
        public readonly bool $required = true,
        public readonly mixed $default = null,
    ) {}
}
