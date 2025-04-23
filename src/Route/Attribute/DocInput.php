<?php
declare(strict_types=1);

namespace LesDocumentor\Route\Attribute;

use Attribute;
use LesValueObject\Composite\CompositeValueObject;

#[Attribute(Attribute::TARGET_CLASS)]
final class DocInput
{
    /**
     * @param class-string<CompositeValueObject> $input
     */
    public function __construct(public readonly string $input)
    {}
}
