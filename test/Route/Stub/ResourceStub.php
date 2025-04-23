<?php
declare(strict_types=1);

namespace LesDocumentorTest\Route\Stub;

use LesValueObject\String\Format\Resource\Identifier;
use LesValueObject\String\Format\Resource\Type;

final class ResourceStub
{
    public function __construct(
        public readonly Identifier $id,
        public readonly Type $type,
    ) {}
}
