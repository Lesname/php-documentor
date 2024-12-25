<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use RuntimeException;
use LessDocumentor\Type\Document\TypeDocument;
use LessDocumentor\Type\Document\Number\Range;
use LessDocumentor\Type\Document\AnyTypeDocument;
use LessDocumentor\Type\Exception\UnexpectedInput;
use LessDocumentor\Type\Document\BoolTypeDocument;
use LessDocumentor\Type\Document\NullTypeDocument;
use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\StringTypeDocument;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\CollectionTypeDocument;

final class BuiltinTypeDocumentor implements TypeDocumentor
{
    public function canDocument(mixed $input): bool
    {
        return is_string($input);
    }

    public function document(mixed $input): TypeDocument
    {
        if (!is_string($input)) {
            throw new UnexpectedInput('string', $input);
        }

        return match ($input) {
            'null' => new NullTypeDocument(),
            'iterable',
            'array' => new CollectionTypeDocument(new AnyTypeDocument(), null),
            'object' => new CompositeTypeDocument([], true),
            'bool' => new BoolTypeDocument(),
            'float' => new NumberTypeDocument(new Range(PHP_FLOAT_MIN, PHP_FLOAT_MAX), null),
            'int' => new NumberTypeDocument(new Range(PHP_INT_MIN, PHP_INT_MAX), 1),
            'mixed' => new AnyTypeDocument(),
            'string' => new StringTypeDocument(null),
            default => throw new RuntimeException("Builtin type '{$input}' not supported"),
        };
    }
}
