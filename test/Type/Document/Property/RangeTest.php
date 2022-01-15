<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type\Document\Property;

use LessDocumentor\Type\Document\Property\Range;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\Document\Property\Range
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
