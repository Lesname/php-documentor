<?php
declare(strict_types=1);

namespace LesDocumentorTest\Type\Document\Property;

use LesDocumentor\Type\Document\String\Length;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LesDocumentor\Type\Document\String\Length
 */
final class LengthTest extends TestCase
{
    public function testSetup(): void
    {
        $size = new Length(1, 2);

        self::assertSame(1, $size->minimal);
        self::assertSame(2, $size->maximal);
    }
}
