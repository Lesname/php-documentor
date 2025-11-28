<?php

declare(strict_types=1);

namespace LesDocumentorTest\Type\Document;

use LesDocumentor\Type\Document\NullTypeDocument;
use LesDocumentor\Type\Document\UnionTypeDocument;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(UnionTypeDocument::class)]
class UnionTypeDocumentTest extends TestCase
{
    public function testContainsNull(): void
    {
        $contains = new UnionTypeDocument([new NullTypeDocument()]);
        $notContains = new UnionTypeDocument([]);

        self::assertTrue($contains->containsNull());
        self::assertFalse($notContains->containsNull());
    }
}
