<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use LessValueObject\String\Exception\TooLong;
use LessValueObject\String\Exception\TooShort;
use LessDocumentor\Type\Document\String\Pattern;
use LessDocumentor\Type\Document\AnyTypeDocument;
use LessDocumentor\Type\Document\BoolTypeDocument;
use LessDocumentor\Type\Document\Collection\Size;
use LessDocumentor\Type\Document\CollectionTypeDocument;
use LessDocumentor\Type\Document\Composite\Property;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\EnumTypeDocument;
use LessDocumentor\Type\Document\Number\Range;
use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\ReferenceTypeDocument;
use LessDocumentor\Type\Document\String\Length;
use LessDocumentor\Type\Document\StringTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;
use LessDocumentor\Type\Document\UnionTypeDocument;
use RuntimeException;

final class OpenApiTypeDocumentor
{
    private const TYPE_STRING = 1;
    private const TYPE_INT = 2;
    private const TYPE_NUMBER = 4;
    private const TYPE_BOOL = 8;
    private const TYPE_OBJECT = 16;
    private const TYPE_ARRAY = 32;
    private const TYPE_NULL = 64;

    private const TYPE_ANY = self::TYPE_STRING
    | self::TYPE_INT
    | self::TYPE_NUMBER
    | self::TYPE_BOOL
    | self::TYPE_OBJECT
    | self::TYPE_ARRAY
    | self::TYPE_NULL;

    /**
     * @param array<mixed> $schema
     *
     * @psalm-suppress DeprecatedMethod
     */
    public function document(array $schema): TypeDocument
    {
        $document = $this->documentType($schema);

        if (isset($schema['deprecated']) && $schema['deprecated']) {
            $document = $document->withDeprecated('deprecated');
        }

        return $document;
    }

    /**
     * @param array<mixed> $schema
     *
     * @psalm-suppress MixedAssignment
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function documentType(array $schema): TypeDocument
    {
        if (isset($schema['$ref'])) {
            assert(is_string($schema['$ref']));

            return new ReferenceTypeDocument($schema['$ref']);
        }

        if (isset($schema['anyOf'])) {
            assert(is_array($schema['anyOf']));

            return $this->documentUnion($schema['anyOf']);
        }

        if (isset($schema['oneOf'])) {
            assert(is_array($schema['oneOf']));

            return $this->documentUnion($schema['oneOf']);
        }

        $nullable = false;

        if (!isset($schema['type'])) {
            throw new RuntimeException('Type required');
        } elseif (is_string($schema['type'])) {
            $type = $schema['type'];
        } elseif (is_array($schema['type'])) {
            $nullable = in_array('null', $schema['type']);
            $types = array_values(
                array_filter(
                    $schema['type'],
                    static fn(string $item) => $item !== 'null',
                ),
            );

            if (count($types) === 1) {
                $type = $types[0];
                assert(is_string($type));
            } else {
                throw new RuntimeException('Types "' . implode(', ', $types) . '"');
            }
        } else {
            throw new RuntimeException('Type can only be a string or array');
        }

        $document = match ($type) {
            'array' => $this->documentArray($schema),
            'boolean' => new BoolTypeDocument(),
            'integer',
            'number' => $this->documentNumber($schema),
            'object' => $this->documentObject($schema),
            'string' => $this->documentString($schema),
            default => throw new RuntimeException("Type '{$type}' not supported"),
        };

        return $nullable
            ? $document->withNullable()
            : $document;
    }

    /**
     * @param array<mixed> $subs
     *
     * @psalm-suppress MixedAssignment
     */
    private function documentUnion(array $subs): TypeDocument
    {
        $toDocTypes = [];
        $bitTypes = 0;

        foreach ($subs as $item) {
            assert(is_array($item));

            if (isset($item['type']) && is_string($item['type'])) {
                if ($item['type'] === 'null') {
                    $bitTypes |= self::TYPE_NULL;
                } else {
                    if (count(array_keys($item)) === 1) {
                        $bitTypes |= match ($item['type']) {
                            'string' => self::TYPE_STRING,
                            'integer' => self::TYPE_INT,
                            'number' => self::TYPE_NUMBER,
                            'boolean' => self::TYPE_BOOL,
                            'object' => self::TYPE_OBJECT,
                            'array' => self::TYPE_ARRAY,
                            default => throw new RuntimeException("Type '{$item['type']}' unknown"),
                        };
                    }

                    $toDocTypes[] = $item;
                }
            } else {
                $toDocTypes[] = $item;
            }
        }

        if (($bitTypes & self::TYPE_ANY) === self::TYPE_ANY) {
            return new AnyTypeDocument();
        }

        if (count($toDocTypes) === 1) {
            $document = $this->document($toDocTypes[0]);
        } elseif (count($toDocTypes) === 0) {
            throw new RuntimeException();
        } else {
            $document = new UnionTypeDocument(
                array_map(
                    function (array $input): TypeDocument {
                        return $this->document($input);
                    },
                    $toDocTypes,
                ),
            );
        }

        return ($bitTypes & self::TYPE_NULL) === self::TYPE_NULL
            ? $document->withNullable()
            : $document;
    }

    /**
     * @param array<mixed> $schema
     */
    private function documentArray(array $schema): TypeDocument
    {
        $minItems = $schema['minItems'] ?? null;
        assert(is_int($minItems) || $minItems === null);

        $maxItems = $schema['maxItems'] ?? null;
        assert(is_int($maxItems) || $maxItems === null);

        return new CollectionTypeDocument(
            isset($schema['items']) && is_array($schema['items'])
                ? $this->document($schema['items'])
                : new AnyTypeDocument(),
            $minItems !== null && $maxItems !== null
                ? new Size($minItems, $maxItems)
                : null,
        );
    }

    /**
     * @param array<mixed> $schema
     */
    private function documentObject(array $schema): TypeDocument
    {
        $extraProperties = $schema['additionalProperties'] ?? false;
        assert(is_bool($extraProperties));

        if (isset($schema['required'])) {
            assert(is_array($schema['required']));
            $required = $schema['required'];
        } else {
            $required = [];
        }

        $properties = [];

        if (isset($schema['properties']) && is_array($schema['properties'])) {
            foreach ($schema['properties'] as $key => $propSchema) {
                assert(is_string($key));
                assert(is_array($propSchema));

                $default = isset($propSchema['default']) && (is_scalar($propSchema['default']) || is_array($propSchema['default']))
                    ? $propSchema['default']
                    : null;

                $properties[$key] = new Property(
                    $this->document($propSchema),
                    in_array($key, $required),
                    $default,
                    isset($propSchema['deprecated']) && $propSchema['deprecated'],
                );
            }
        }

        return new CompositeTypeDocument(
            $properties,
            $extraProperties,
        );
    }

    /**
     * @param array<mixed> $schema
     *
     * @psalm-suppress MixedArgumentTypeCoercion
     *
     * @throws TooLong
     * @throws TooShort
     */
    private function documentString(array $schema): TypeDocument
    {
        if (isset($schema['enum'])) {
            assert(is_array($schema['enum']));

            return new EnumTypeDocument($schema['enum']);
        }

        $minLength = $schema['minLength'] ?? null;
        assert(is_int($minLength) || $minLength === null);

        $maxLength = $schema['maxLength'] ?? null;
        assert(is_int($maxLength) || $maxLength === null);

        return new StringTypeDocument(
            $minLength !== null && $maxLength !== null
                ? new Length($minLength, $maxLength)
                : null,
            isset($schema['format']) && is_string($schema['format'])
                ? $schema['format']
                : null,
            isset($schema['pattern']) && is_string($schema['pattern'])
                ? new Pattern($schema['pattern'])
                : null,
        );
    }

    /**
     * @param array<mixed> $schema
     */
    private function documentNumber(array $schema): TypeDocument
    {
        $minimum = $schema['minimum'] ?? null;
        assert(is_float($minimum) || is_int($minimum) || $minimum === null);

        $maximum = $schema['maximum'] ?? null;
        assert(is_float($maximum) || is_int($maximum) || $maximum === null);

        $multipleOf = null;

        if (isset($schema['multipleOf']) && (is_int($schema['multipleOf']) || is_float($schema['multipleOf']))) {
            $multipleOf = $schema['multipleOf'];
            $multipleOfStr = (string)$multipleOf;

            if (str_contains($multipleOfStr, '.')) {
                $pos = strpos($multipleOfStr, '.');
                $precision = $pos !== false
                    ? strlen(substr($multipleOfStr, $pos + 1))
                    : null;
            } else {
                $precision = 0;
            }
        } else {
            $precision = null;
        }

        return new NumberTypeDocument(
            $minimum !== null && $maximum !== null
                ? new Range($minimum, $maximum)
                : null,
            $multipleOf,
            $precision,
        );
    }
}
