<?php
declare(strict_types=1);

namespace LesDocumentorTest\Type\Document\Composite\Key;

use LesDocumentor\Type\Document\Composite\Key\AnyKey;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(AnyKey::class)]
class AnyKeyTest extends TestCase
{
    public function testMatches(): void
    {
        $key = new AnyKey();

        $str = bin2hex(random_bytes(6));

        self::assertTrue($key->matches($str));
    }
}
