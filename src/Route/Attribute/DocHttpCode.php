<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class DocHttpCode
{
    public function __construct(public int $code)
    {
        assert($code >= 200 && $code <= 599);
    }
}
