<?php

declare(strict_types=1);

namespace LesDocumentorTest\Type\Document\Composite\Key;

use LesDocumentor\Type\Document\Composite\Key\RegexKey;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(RegexKey::class)]
class RegexKeyTest extends TestCase
{
    public function testMatches(): void
    {
        $key = new RegexKey('f');

        self::assertTrue($key->matches('foo'));
        self::assertFalse($key->matches('bar'));
    }
}
