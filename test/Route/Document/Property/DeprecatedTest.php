<?php
declare(strict_types=1);

namespace LesDocumentorTest\Route\Document\Property;

use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Route\Document\Property\Deprecated;
use PHPUnit\Framework\TestCase;

#[CoversClass(\LesDocumentor\Route\Document\Property\Deprecated::class)]
final class DeprecatedTest extends TestCase
{
    public function testSetup(): void
    {
        $deprecated = new Deprecated('alt', 'reason');

        self::assertSame('alt', $deprecated->alternate);
        self::assertSame('reason', $deprecated->reason);
    }
}
