<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route\Stub;

use LessDocumentor\Route\Attribute\DocResource;
use LessValueObject\Number\Int\Date\Timestamp;
use LessValueObject\Number\Int\Paginate\Page;
use LessValueObject\String\Format\Resource\Type;

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
