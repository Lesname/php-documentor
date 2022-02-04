<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route;

use LessValueObject\Number\Int\Date\Timestamp;
use LessValueObject\Number\Int\Paginate\Page;
use LessValueObject\String\Format\Resource\Type;

final class ClassProxyStub
{
    public function foo(Type $type, Timestamp $fiz): Page
    {}
}
