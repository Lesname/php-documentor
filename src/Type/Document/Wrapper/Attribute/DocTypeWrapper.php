<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document\Wrapper\Attribute;

use Attribute;
use LesDocumentor\Type\Document\Wrapper\TypeDocumentWrapper;

#[Attribute(Attribute::TARGET_CLASS)]
final class DocTypeWrapper
{
    /**
     * @param class-string<TypeDocumentWrapper> $typeWrapper
     */
    public function __construct(public readonly string $typeWrapper)
    {}
}
