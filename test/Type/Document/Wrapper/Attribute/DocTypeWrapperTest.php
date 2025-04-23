<?php
declare(strict_types=1);

namespace LesDocumentorTest\Type\Document\Wrapper\Attribute;

use LesDocumentor\Type\Document\Wrapper\Attribute\DocTypeWrapper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LesDocumentor\Type\Document\Wrapper\Attribute\DocTypeWrapper
 */
final class DocTypeWrapperTest extends TestCase
{
    public function testConstruct(): void
    {
        $attr = new DocTypeWrapper('fiz');

        self::assertSame('fiz', $attr->typeWrapper);
    }
}
