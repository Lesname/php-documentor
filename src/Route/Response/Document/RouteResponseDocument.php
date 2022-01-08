<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Response\Document;

use LessDocumentor\Route\Response\Document\Property\Code;
use LessDocumentor\Type\Document\TypeDocument;

/**
 * @psalm-immutable
 */
interface RouteResponseDocument
{
    public function getCode(): Code;

    public function getOutput(): ?TypeDocument;
}
