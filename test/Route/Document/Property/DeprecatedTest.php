<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route\Document\Property;

use LessDocumentor\Route\Document\Property\Deprecated;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Route\Document\Property\Deprecated
 */
final class DeprecatedTest extends TestCase
{
    public function testSetup(): void
    {
        $deprecated = new Deprecated('alt', 'reason');

        self::assertSame('alt', $deprecated->alternate);
        self::assertSame('reason', $deprecated->reason);
    }
}
