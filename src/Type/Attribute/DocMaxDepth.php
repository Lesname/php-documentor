<?php

declare(strict_types=1);

namespace LesDocumentor\Type\Attribute;

use Attribute;

/**
 * @psalm-immutable
 */
#[Attribute(Attribute::TARGET_CLASS)]
final class DocMaxDepth
{
    public function __construct(public readonly int $maxDepth)
    {}
}
