<?php
declare(strict_types=1);

namespace LessDocumentor\Type\Document\Wrapper;

use LessDocumentor\Type\Document\TypeDocument;

interface TypeDocumentWrapper
{
    public function wrap(TypeDocument $typeDocument): TypeDocument;
}
