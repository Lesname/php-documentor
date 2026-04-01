<?php

declare(strict_types=1);

namespace LesDocumentorTest\Type;

use LesDocumentor\Type\Attribute\DocSkip;
use LesValueObject\Number\Int\Paginate\Page;
use LesValueObject\Number\Int\Date\Timestamp;
use LesValueObject\Number\Int\Paginate\PerPage;
use LesDocumentor\Type\Attribute\DocDeprecated;
use LesDocumentor\Type\MethodParametersTypeDocumentor;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Type\Document\CompositeTypeDocument;

#[CoversClass(MethodParametersTypeDocumentor::class)]
class MethodParametersTypeDocumentorTest extends TestCase
{
    public function testDocumentSkip(): void
    {
        $class = new class {
            public function foo(
                Timestamp $timestamp,
                #[DocSkip]
                PerPage $perPage,
                #[DocDeprecated]
                Page $page,
            ) {}
        };

        $method = new \ReflectionMethod($class, 'foo');

        $documentor = new MethodParametersTypeDocumentor();
        $document = $documentor->document($method);

        self::assertInstanceOf(CompositeTypeDocument::class, $document);

        $properties = $document->properties;
        self::assertSame(2, count($properties));
    }
}
