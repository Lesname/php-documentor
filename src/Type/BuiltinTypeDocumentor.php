<?php
declare(strict_types=1);

namespace LesDocumentor\Type;

use Override;
use RuntimeException;
use LesDocumentor\Type\Document\TypeDocument;
use LesDocumentor\Type\Document\Number\Range;
use LesDocumentor\Type\Document\AnyTypeDocument;
use LesDocumentor\Type\Exception\UnexpectedInput;
use LesDocumentor\Type\Document\BoolTypeDocument;
use LesDocumentor\Type\Document\NullTypeDocument;
use LesDocumentor\Type\Document\NumberTypeDocument;
use LesDocumentor\Type\Document\StringTypeDocument;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\CollectionTypeDocument;

final class BuiltinTypeDocumentor implements TypeDocumentor
{
    #[Override]
    public function canDocument(mixed $input): bool
    {
        return is_string($input);
    }

    #[Override]
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
