<?php

declare(strict_types=1);

namespace LesDocumentor\Route\Input;

use LesDocumentor\Type\Document\TypeDocument;

interface RouteInputDocumentor
{
    /**
     * @param array<mixed> $route
     */
    public function document(array $route): TypeDocument;
}
