<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type\Document\Wrapper\Attribute;

use LessDocumentor\Type\Document\Wrapper\Attribute\DocTypeWrapper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\Document\Wrapper\Attribute\DocTypeWrapper
 */
final class DocTypeWrapperTest extends TestCase
{
    public function testConstruct(): void
    {
        $attr = new DocTypeWrapper('fiz');

        self::assertSame('fiz', $attr->typeWrapper);
    }
}
