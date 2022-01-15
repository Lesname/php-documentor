<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document;

use LessDocumentor\Route\Document\Property\Deprecated;
use LessDocumentor\Route\Document\Property\Method;
use LessDocumentor\Type\Document\TypeDocument;

/**
 * @psalm-immutable
 */
interface RouteDocument
{
    public function getMethod(): Method;

    public function getPath(): string;

    public function getResource(): string;

    public function getDeprecated(): ?Deprecated;

    /**
     * @return array<string, TypeDocument>
     */
    public function getInput(): array;

    /**
     * @return array<Property\Response>
     */
    public function getRespones(): array;
}
