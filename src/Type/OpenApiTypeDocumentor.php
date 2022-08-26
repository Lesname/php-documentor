<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

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
use RuntimeException;

final class OpenApiTypeDocumentor
{
    /**
     * @param array<mixed> $schema
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

            return $this->documenyAnyOf($schema);
        }

        $nullable = false;

        if (!isset($schema['type'])) {
            throw new RuntimeException('Type required');
        } elseif (is_string($schema['type'])) {
            $type = $schema['type'];
        } elseif (is_array($schema['type'])) {
            $nullable = in_array('null', $schema['type']);
            $type = $this->filterOut($schema['type'], 'null');

            if (count($type) === 1) {
                $type = $type[0];
                assert(is_string($type));
            } else {
                throw new RuntimeException('Types "' . implode(',', $type) . '"');
            }
        } else {
            throw new RuntimeException();
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
     * @param array<mixed> $schema
     *
     * @psalm-suppress MixedAssignment
     */
    private function documenyAnyOf(array $schema): TypeDocument
    {
        $nullable = false;

        assert(is_array($schema['anyOf']));

        foreach ($schema['anyOf'] as $item) {
            if ($item === ['type' => 'null']) {
                $nullable = true;

                break;
            }
        }

        $any = $this->filterOut($schema['anyOf'], ['type' => 'null']);

        if (count($any) === 1) {
            assert(is_array($any[0]));
            $document = $this->document($any[0]);
        } else {
            $count = count($any);

            throw new RuntimeException("Failed any with count {$count}");
        }

        return $nullable
            ? $document->withNullable()
            : $document;
    }

    /**
     * @param array<mixed> $schema
     */
    private function documentArray(array $schema): TypeDocument
    {
        assert(is_array($schema['items']));

        $minItems = $schema['minItems'] ?? null;
        assert(is_int($minItems) || $minItems === null);

        $maxItems = $schema['maxItems'] ?? null;
        assert(is_int($maxItems) || $maxItems === null);

        return new CollectionTypeDocument(
            $this->document($schema['items']),
            new Size($minItems, $maxItems),
        );
    }

    /**
     * @param array<mixed> $schema
     */
    private function documentObject(array $schema): TypeDocument
    {
        $extraProperties = $schema['additionalProperties'] ?? false;
        assert(is_bool($extraProperties));

        assert(is_array($schema['properties']));

        if (isset($schema['required'])) {
            assert(is_array($schema['required']));
            $required = $schema['required'];
        } else {
            $required = [];
        }

        $properties = [];

        foreach ($schema['properties'] as $key => $propSchema) {
            assert(is_string($key));
            assert(is_array($propSchema));

            $properties[$key] = new Property(
                $this->document($propSchema),
                in_array($key, $required),
                $properties['default'] ?? null,
                isset($propSchema['deprecated']) && $propSchema['deprecated'],
            );
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
            new Length($minLength, $maxLength),
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

        $multipleOf = $schema['multipleOf'] ?? 1;
        assert(is_int($multipleOf) || is_float($multipleOf));

        return new NumberTypeDocument(
            new Range($minimum, $maximum),
            strlen((string)(1 / $multipleOf)),
        );
    }

    /**
     * @param array<mixed> $array
     *
     * @return array<mixed>
     */
    private function filterOut(array $array, mixed $value): array
    {
        return array_values(
            array_unique(
                array_filter(
                    $array,
                    static fn (mixed $t): bool => $t !== $value,
                ),
            )
        );
    }
}
