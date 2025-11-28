<?php

declare(strict_types=1);

namespace LesDocumentorTest\Type\Document\Wrapper\Attribute;

use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Type\Document\Wrapper\Attribute\DocTypeWrapper;
use PHPUnit\Framework\TestCase;

#[CoversClass(\LesDocumentor\Type\Document\Wrapper\Attribute\DocTypeWrapper::class)]
final class DocTypeWrapperTest extends TestCase
{
    public function testConstruct(): void
    {
        $attr = new DocTypeWrapper('fiz');

        self::assertSame('fiz', $attr->typeWrapper);
    }
}
