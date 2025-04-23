<?php
declare(strict_types=1);

namespace LesDocumentorTest\Type\Document\Property;

use LesDocumentor\Type\Document\Number\Range;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LesDocumentor\Type\Document\Number\Range
 */
final class RangeTest extends TestCase
{
    public function testSetup(): void
    {
        $size = new Range(1, 2);

        self::assertSame(1, $size->minimal);
        self::assertSame(2, $size->maximal);
    }
}
