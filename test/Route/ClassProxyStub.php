<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route;

use LessDocumentor\Route\Attribute\DocResource;
use LessResource\Model\AbstractResourceModel;
use LessResource\Model\ResourceModel;
use LessResource\Set\ResourceSet;
use LessValueObject\Number\Int\Date\Timestamp;
use LessValueObject\Number\Int\Paginate\Page;
use LessValueObject\String\Format\Resource\Type;

#[DocResource(AbstractResourceModel::class)]
final class ClassProxyStub
{
    public function foo(Type $type, Timestamp $fiz): Page
    {}

    public function bar(): ResourceModel
    {}

    public function biz(): ResourceSet
    {}
}
