<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type\Document\Property;

use LessDocumentor\Type\Document\Property\Length;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\Document\Property\Length
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
