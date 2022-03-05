<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Input;

use LessDocumentor\Type\Document\TypeDocument;

interface RouteInputDocumentor
{
    /**
     * @param array<mixed> $route
     */
    public function document(array $route): TypeDocument;
}
