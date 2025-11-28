<?php

declare(strict_types=1);

namespace LesDocumentor\Type;

use LesDocumentor\Type\Document\TypeDocument;
use LesDocumentor\Type\Exception\UnexpectedInput;

interface TypeDocumentor
{
    public function canDocument(mixed $input): bool;

    /**
     * @throws UnexpectedInput
     */
    public function document(mixed $input): TypeDocument;
}
