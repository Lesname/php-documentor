<?php
declare(strict_types=1);

namespace LessDocumentorTest\Helper;

use LessDocumentor\Helper\AttributeHelper;
use LessDocumentor\Route\Exception\MissingAttribute;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use stdClass;

/**
 * @covers \LessDocumentor\Helper\AttributeHelper
 */
final class AttributeHelperTest extends TestCase
{
    public function testGetAttributes(): void
    {
        $class = new
        #[AttributeStub('fiz')]
        #[AttributeStub('bar')]
        class () {
        };

        $refClass = new ReflectionClass($class::class);

        $attributes = AttributeHelper::getAttributes($refClass, AttributeStub::class);

        self::assertSame(2, count($attributes));

        foreach ($attributes as $i => $attribute) {
            self::assertSame($i === 0 ? 'fiz' : 'bar', $attribute->value);
        }
    }

    public function testGetAttribute(): void
    {
        $class = new
        #[AttributeStub('fiz')]
        #[AttributeStub('bar')]
        class () {
        };

        $refClass = new ReflectionClass($class::class);

        $attribute = AttributeHelper::getAttribute($refClass, AttributeStub::class);

        self::assertSame('fiz', $attribute->value);
    }

    public function testGetAttributeMissing(): void
    {
        $this->expectException(MissingAttribute::class);

        $class = new
        #[AttributeStub('fiz')]
        #[AttributeStub('bar')]
        class () {
        };

        $refClass = new ReflectionClass($class::class);
        AttributeHelper::getAttribute($refClass, stdClass::class);
    }

    public function testHasAttribute(): void
    {
        $class = new #[AttributeStub('bar')] class () {
        };

        $refClass = new ReflectionClass($class::class);
        self::assertTrue(AttributeHelper::hasAttribute($refClass, AttributeStub::class));
        self::assertFalse(AttributeHelper::hasAttribute($refClass, stdClass::class));
    }
}
