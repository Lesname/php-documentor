<?php

declare(strict_types=1);

namespace LesDocumentorTest\Route\Document\Property;

use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Route\Document\Property\Path;
use PHPUnit\Framework\TestCase;

#[CoversClass(\LesDocumentor\Route\Document\Property\Path::class)]
final class PathTest extends TestCase
{
    public function testGetResource(): void
    {
        $path = new Path('/fiz/biz.bar.foo');

        self::assertSame('biz.bar', $path->getResource());
    }

    public function testGetAction(): void
    {
        $path = new Path('/fiz/biz.bar.foo');

        self::assertSame('foo', $path->getAction());
    }
}
