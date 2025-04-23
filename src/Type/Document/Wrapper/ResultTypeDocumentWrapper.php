<?php
declare(strict_types=1);

namespace LesDocumentor\Type\Document\Wrapper;

use Override;
use LesDocumentor\Type\Document\Composite\Property;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\TypeDocument;

final class ResultTypeDocumentWrapper implements TypeDocumentWrapper
{
    #[Override]
    public function wrap(TypeDocument $typeDocument): TypeDocument
    {
        return new CompositeTypeDocument(['result' => new Property($typeDocument)]);
    }
}
