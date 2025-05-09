<?php
declare(strict_types=1);

namespace LesDocumentor\Helper;

use ReflectionMethod;
use LesDocumentor\Route\Exception\MissingAttribute;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionParameter;
use ReflectionProperty;

/**
 * @internal
 */
final class AttributeHelper
{
    /**
     * @param ReflectionClass<covariant object>|ReflectionProperty|ReflectionParameter|ReflectionMethod $reflector
     * @param class-string<T> $nameAttribute
     *
     * @template T
     *
     * @return array<T>
     */
    public static function getAttributes(ReflectionClass|ReflectionProperty|ReflectionParameter|ReflectionMethod $reflector, string $nameAttribute): array
    {
        return array_map(
            static fn (ReflectionAttribute $attribute) => $attribute->newInstance(),
            $reflector->getAttributes($nameAttribute)
        );
    }

    /**
     * @param ReflectionClass<covariant object>|ReflectionProperty|ReflectionParameter|ReflectionMethod $reflector
     * @param class-string<T> $nameAttribute
     *
     * @template T
     *
     * @return T
     *
     * @throws MissingAttribute
     */
    public static function getAttribute(ReflectionClass|ReflectionProperty|ReflectionParameter|ReflectionMethod $reflector, string $nameAttribute)
    {
        foreach ($reflector->getAttributes($nameAttribute) as $attribute) {
            return $attribute->newInstance();
        }

        throw new MissingAttribute((string)$reflector, $nameAttribute);
    }

    /**
     * @param ReflectionClass<covariant object>|ReflectionProperty|ReflectionParameter|ReflectionMethod $reflector
     * @param class-string $nameAttribute
     */
    public static function hasAttribute(ReflectionClass|ReflectionProperty|ReflectionParameter|ReflectionMethod $reflector, string $nameAttribute): bool
    {
        return count($reflector->getAttributes($nameAttribute)) > 0;
    }
}
