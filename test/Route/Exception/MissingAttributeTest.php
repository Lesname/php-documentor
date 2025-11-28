<?php

declare(strict_types=1);

namespace LesDocumentorTest\Route\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Route\Exception\MissingAttribute;
use PHPUnit\Framework\TestCase;

#[CoversClass(\LesDocumentor\Route\Exception\MissingAttribute::class)]
final class MissingAttributeTest extends TestCase
{
    public function testConstruct(): void
    {
        $e = new MissingAttribute('fiz', 'biz');

        self::assertSame('fiz', $e->reflecting);
        self::assertSame('biz', $e->attribute);
    }
}
