<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document\Wrapper;

use Override;
use LesDocumentor\Type\Document\Composite\Property;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\Number\Range;
use LesDocumentor\Type\Document\NumberTypeDocument;
use LesDocumentor\Type\Document\TypeDocument;
use LesDocumentor\Type\Document\Composite\Key\ExactKey;

final class ResultsTypeDocumentWrapper implements TypeDocumentWrapper
{
    #[Override]
    public function wrap(TypeDocument $typeDocument): TypeDocument
    {
        return new CompositeTypeDocument(
            [
                new Property(new ExactKey('results'), $typeDocument),
                new Property(
                    new ExactKey('meta'),
                    new CompositeTypeDocument(
                        [
                            new Property(
                                new ExactKey('total'),
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
