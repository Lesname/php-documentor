<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Attribute;

use LessValueObject\ValueObject;

#[Attribute(Attribute::TARGET_CLASS)]
final class DocHttpOutput
{
    /**
     * @param class-string<ValueObject> $output
     */
    public function __construct(public string $output)
    {}
}
