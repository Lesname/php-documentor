<?php
declare(strict_types=1);

namespace LesDocumentor\Route\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class DocResource
{
    /**
     * @param class-string $resource
     */
    public function __construct(public readonly string $resource)
    {}
}
