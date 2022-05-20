<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document\Wrapper;

use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\Property\Range;
use LessDocumentor\Type\Document\TypeDocument;
use LessValueObject\Number\Int\Unsigned;

final class ResultsTypeDocumentWrapper implements TypeDocumentWrapper
{
    public function wrap(TypeDocument $typeDocument): TypeDocument
    {
        return new CompositeTypeDocument(
            [
                'results' => $typeDocument,
                'meta' => new CompositeTypeDocument(
                    [
                        'total' => new NumberTypeDocument(
                            new Range(0, PHP_INT_MAX),
                            new Unsigned(0),
                            null,
                        ),
                    ],
                    ['total'],
                ),
            ],
            ['results', 'meta'],
        );
    }
}
