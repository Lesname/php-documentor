<?php
declare(strict_types=1);

namespace LesDocumentorTest\Helper;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_ALL)]
final class AttributeStub
{
    public function __construct(public readonly string $value)
    {}
}
