<?php
declare(strict_types=1);

namespace LessDocumentor\Route;

use LessDocumentor\Route\Document\RouteDocument;

interface RouteDocumentor
{
    /**
     * @param array<mixed> $route
     */
    public function document(array $route): RouteDocument;
}
