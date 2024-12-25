<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use LessDocumentor\Type\Document\TypeDocument;
use LessDocumentor\Type\Exception\UnexpectedInput;

interface TypeDocumentor
{
    public function canDocument(mixed $input): bool;

    /**
     * @throws UnexpectedInput
     */
    public function document(mixed $input): TypeDocument;
}
