<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route\Document\Property;

use LessDocumentor\Route\Document\Property\Path;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Route\Document\Property\Path
 */
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
