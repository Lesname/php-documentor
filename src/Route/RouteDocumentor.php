<?php

declare(strict_types=1);

namespace LesDocumentor\Route;

use LesDocumentor\Route\Document\RouteDocument;

interface RouteDocumentor
{
    /**
     * @param array<mixed> $route
     */
    public function document(array $route): RouteDocument;
}
