<?php

declare(strict_types=1);

namespace LesDocumentorTest\Type\Document\Composite\Key;

use LesDocumentor\Type\Document\Composite\Key\ExactKey;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(ExactKey::class)]
class ExactKeyTest extends TestCase
{
    public function testMatches(): void
    {
        $key = new ExactKey('fiz');

        self::assertTrue($key->matches('fiz'));
    }

    public function testNotMatches(): void
    {
        $key = new ExactKey('fiz');

        self::assertFalse($key->matches('bar'));
    }
}
