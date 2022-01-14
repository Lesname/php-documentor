<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route\Attribute;

use LessDocumentor\Route\Attribute\DocHttpOutput;
use LessValueObject\ValueObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Route\Attribute\DocHttpOutput
 */
final class DocHttpOutputTest extends TestCase
{
    public function testSetup(): void
    {
        $attr = new DocHttpOutput(ValueObject::class);

        self::assertSame(ValueObject::class, $attr->output);
    }
}
