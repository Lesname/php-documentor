<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route\Stub;

use LessValueObject\String\Format\Resource\Identifier;
use LessValueObject\String\Format\Resource\Type;

final class ResourceStub
{
    public function __construct(
        public readonly Identifier $id,
        public readonly Type $type,
    ) {}
}
