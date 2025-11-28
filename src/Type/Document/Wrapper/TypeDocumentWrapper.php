<?php

declare(strict_types=1);

namespace LesDocumentor\Type\Document\Wrapper;

use LesDocumentor\Type\Document\TypeDocument;

interface TypeDocumentWrapper
{
    public function wrap(TypeDocument $typeDocument): TypeDocument;
}
