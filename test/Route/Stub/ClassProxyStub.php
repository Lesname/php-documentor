<?php
declare(strict_types=1);

namespace LesDocumentorTest\Route\Stub;

use LesDocumentor\Route\Attribute\DocResource;
use LesValueObject\Number\Int\Date\Timestamp;
use LesValueObject\Number\Int\Paginate\Page;
use LesValueObject\String\Format\Resource\Type;

#[DocResource(ResourceStub::class)]
final class ClassProxyStub
{
    public function foo(Type $type, Timestamp $fiz): Page
    {}

    public function bar(): InterfaceStub
    {}

    public function biz(): TraversableStub
    {}
}
