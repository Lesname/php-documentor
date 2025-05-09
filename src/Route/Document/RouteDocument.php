<?php
declare(strict_types=1);

namespace LesDocumentor\Route\Document;

use LesDocumentor\Type\Document\TypeDocument;
use LesValueObject\Composite\AbstractCompositeValueObject;

/**
 * @psalm-immutable
 */
final class RouteDocument extends AbstractCompositeValueObject
{
    /** @deprecated */
    public readonly Property\Category $category;

    /**
     * @param array<int, Property\Response> $responses
     */
    public function __construct(
        public readonly Property\Method $method,
        Property\Category $category,
        public readonly Property\Path $path,
        public readonly Property\Resource $resource,
        public readonly ?Property\Deprecated $deprecated,
        public readonly TypeDocument $input,
        public readonly array $responses,
    ) {
        $this->category = $category;
    }
}
