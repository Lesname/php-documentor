<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
final class DocHttpProxy
{
    /**
     * @param class-string $class
     * @param string $method
     */
    public function __construct(
        public readonly string $class,
        public readonly string $method,
    ) {}
}
