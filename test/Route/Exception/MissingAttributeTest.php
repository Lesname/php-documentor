<?php
declare(strict_types=1);

namespace LesDocumentorTest\Route\Exception;

use LesDocumentor\Route\Exception\MissingAttribute;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LesDocumentor\Route\Exception\MissingAttribute
 */
final class MissingAttributeTest extends TestCase
{
    public function testConstruct(): void
    {
        $e = new MissingAttribute('fiz', 'biz');

        self::assertSame('fiz', $e->reflecting);
        self::assertSame('biz', $e->attribute);
    }
}
