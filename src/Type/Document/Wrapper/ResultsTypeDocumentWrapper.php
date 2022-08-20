<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document\Wrapper;

use LessDocumentor\Type\Document\Composite\Property;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\Number\Range;
use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;

final class ResultsTypeDocumentWrapper implements TypeDocumentWrapper
{
    public function wrap(TypeDocument $typeDocument): TypeDocument
    {
        return new CompositeTypeDocument(
            [
                'results' => new Property($typeDocument),
                'meta' => new Property(
                    new CompositeTypeDocument(
                        [
                            'total' => new Property(
                                new NumberTypeDocument(
                                    new Range(0, PHP_INT_MAX),
                                    0,
                                    null,
                                ),
                            ),
                        ],
                    ),
                ),
            ],
        );
    }
}
