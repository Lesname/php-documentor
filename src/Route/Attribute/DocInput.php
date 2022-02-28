<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Attribute;

use Attribute;
use LessValueObject\Composite\CompositeValueObject;

#[Attribute(Attribute::TARGET_CLASS)]
final class DocInput
{
    /**
     * @param class-string<CompositeValueObject> $input
     */
    public function __construct(public readonly string $input)
    {}
}
