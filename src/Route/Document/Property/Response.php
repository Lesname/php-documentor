<?php
declare(strict_types=1);

namespace LessDocumentor\Route\Document\Property;

use LessDocumentor\Type\Document\TypeDocument;
use LessValueObject\Composite\AbstractCompositeValueObject;

/**
 * @psalm-immutable
 */
final class Response extends AbstractCompositeValueObject
{
    public function __construct(
        public ResponseCode $code,
        public ?TypeDocument $output,
    ) {}
}
