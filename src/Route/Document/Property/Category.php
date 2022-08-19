<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document\Property;

/**
 * @psalm-immutable
 */
enum Category: string
{
    case Command = 'command';
    case Query = 'query';
}
