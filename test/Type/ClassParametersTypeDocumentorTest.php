<?php
declare(strict_types=1);

namespace LesDocumentorTest\Type;

use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Type\Document\BoolTypeDocument;
use LesDocumentor\Type\Document\NullTypeDocument;
use LesDocumentor\Type\Document\EnumTypeDocument;
use LesDocumentor\Type\Document\UnionTypeDocument;
use LesDocumentor\Type\ClassParametersTypeDocumentor;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\NumberTypeDocument;
use LesValueObject\Number\Int\Paginate\PerPage;
use PHPUnit\Framework\TestCase;

#[CoversClass(\LesDocumentor\Type\ClassParametersTypeDocumentor::class)]
final class ClassParametersTypeDocumentorTest extends TestCase
{
    public function testObject(): void
    {
        $composite = new class (new PerPage(12), EnumStub::Fiz, true) {
            public function __construct(
                public PerPage $perPage,
                private ?EnumStub $stub,
                private bool $biz = true,
            ) {}
        };

        $documentor = new ClassParametersTypeDocumentor();
        $document = $documentor->document($composite::class);

        self::assertInstanceOf(CompositeTypeDocument::class, $document);
        self::assertSame($composite::class, $document->getReference());
        self::assertNull($document->getDescription());

        self::assertSame(3, count($document->properties));

        $perPage = $document->properties[0];
        self::assertTrue($perPage->key->matches('perPage'));
        self::assertInstanceOf(NumberTypeDocument::class, $perPage->type);
        self::assertSame(0, $perPage->type->range->minimal);
        self::assertSame(100, $perPage->type->range->maximal);
        self::assertSame(PerPage::class, $perPage->type->getReference());
        self::assertNull($perPage->type->getDescription());

        $stub = $document->properties[1];
        self::assertTrue($stub->key->matches('stub'));
        self::assertInstanceOf(UnionTypeDocument::class, $stub->type);
        self::assertInstanceOf(NullTypeDocument::class, $stub->type->subTypes[0]);
        self::assertInstanceOf(EnumTypeDocument::class, $stub->type->subTypes[1]);
        self::assertSame(['foo', 'fiz'], $stub->type->subTypes[1]->cases);
        self::assertSame(EnumStub::class, $stub->type->subTypes[1]->getReference());
        self::assertNull($stub->type->subTypes[1]->getDescription());

        $biz = $document->properties[2];
        self::assertTrue($biz->key->matches('biz'));
        self::assertInstanceOf(BoolTypeDocument::class, $biz->type);
        self::assertTrue($biz->default);
    }
}
