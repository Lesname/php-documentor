<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document\Wrapper;

use LessDocumentor\Type\Document\Composite\Property;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;

final class ResultTypeDocumentWrapper implements TypeDocumentWrapper
{
    public function wrap(TypeDocument $typeDocument): TypeDocument
    {
        return new CompositeTypeDocument(['result' => new Property($typeDocument)]);
    }
}
