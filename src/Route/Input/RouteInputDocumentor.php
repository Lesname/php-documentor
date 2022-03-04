<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Input;

use LessDocumentor\Type\Document\TypeDocument;

interface RouteInputDocumentor
{
    /**
     * @param array<mixed> $route
     *
     * @return array<string, TypeDocument>
     */
    public function document(array $route): array;
}
