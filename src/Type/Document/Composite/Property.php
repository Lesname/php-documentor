<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document\Composite;

use LessDocumentor\Type\Document\TypeDocument;

/**
 * @psalm-immutable
 */
final class Property
{
    /**
     * @param string|int|bool|null|array<string|int|bool|null|float>|float $default
     */
    public function __construct(
        public readonly TypeDocument $type,
        public readonly bool $required = true,
        public readonly string|int|bool|null|array|float $default = null,
        public readonly bool $deprecated = false,
    ) {}
}
