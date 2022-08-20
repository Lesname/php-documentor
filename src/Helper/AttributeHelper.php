<?php
declare(strict_types=1);

namespace LessDocumentor\Helper;

use LessDocumentor\Route\Exception\MissingAttribute;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionParameter;
use ReflectionProperty;

/**
 * @internal
 */
final class AttributeHelper
{
    private function __construct()
    {}

    /**
     * @param ReflectionClass<object> $reflector
     * @param class-string<T> $nameAttribute
     *
     * @template T
     *
     * @return array<T>
     */
    public static function getAttributes(ReflectionClass $reflector, string $nameAttribute): array
    {
        return array_map(
            static fn (ReflectionAttribute $attribute) => $attribute->newInstance(),
            $reflector->getAttributes($nameAttribute)
        );
    }

    /**
     * @param ReflectionClass<object> $reflector
     * @param class-string<T> $nameAttribute
     *
     * @template T
     *
     * @return T
     *
     * @throws MissingAttribute
     */
    public static function getAttribute(ReflectionClass $reflector, string $nameAttribute)
    {
        foreach ($reflector->getAttributes($nameAttribute) as $attribute) {
            return $attribute->newInstance();
        }

        throw new MissingAttribute((string)$reflector, $nameAttribute);
    }

    /**
     * @param ReflectionClass<object>|ReflectionProperty|ReflectionParameter $reflector
     * @param class-string $nameAttribute
     */
    public static function hasAttribute(ReflectionClass|ReflectionProperty|ReflectionParameter $reflector, string $nameAttribute): bool
    {
        return count($reflector->getAttributes($nameAttribute)) > 0;
    }
}
