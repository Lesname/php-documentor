<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document\Wrapper;

use Override;
use LesDocumentor\Type\Document\Composite\Property;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\Number\Range;
use LesDocumentor\Type\Document\NumberTypeDocument;
use LesDocumentor\Type\Document\TypeDocument;

final class ResultsTypeDocumentWrapper implements TypeDocumentWrapper
{
    #[Override]
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
                                    1,
                                ),
                            ),
                        ],
                    ),
                ),
            ],
        );
    }
}
