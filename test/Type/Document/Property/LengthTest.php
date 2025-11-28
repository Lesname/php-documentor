<?php

declare(strict_types=1);

namespace LesDocumentorTest\Type\Document\Property;

use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Type\Document\String\Length;
use PHPUnit\Framework\TestCase;

#[CoversClass(\LesDocumentor\Type\Document\String\Length::class)]
final class LengthTest extends TestCase
{
    public function testSetup(): void
    {
        $size = new Length(1, 2);

        self::assertSame(1, $size->minimal);
        self::assertSame(2, $size->maximal);
    }
}
