<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type;

use LessDocumentor\Type\Document\BoolTypeDocument;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\ObjectInputTypeDocumentor;
use LessValueObject\Number\Int\Paginate\PerPage;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\ObjectInputTypeDocumentor
 */
final class ObjectInputTypeDocumentorTest extends TestCase
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

        $documentor = new ObjectInputTypeDocumentor();
        $document = $documentor->document($composite::class);

        self::assertInstanceOf(CompositeTypeDocument::class, $document);
        self::assertSame($composite::class, $document->getReference());
        self::assertNull($document->getDescription());
        self::assertNull($document->getDeprecated());

        self::assertSame(3, count($document->properties));

        $perPage = $document->properties['perPage'];
        self::assertInstanceOf(NumberTypeDocument::class, $perPage->type);
        self::assertSame(0, $perPage->type->range->minimal);
        self::assertSame(100, $perPage->type->range->maximal);
        self::assertSame(PerPage::class, $perPage->type->getReference());
        self::assertNull($perPage->type->getDescription());
        self::assertNull($perPage->type->getDeprecated());

        $stub = $document->properties['stub'];
        self::assertSame(['foo', 'fiz'], $stub->type->cases);
        self::assertSame(EnumStub::class, $stub->type->getReference());
        self::assertNull($stub->type->getDescription());
        self::assertNull($stub->type->getDeprecated());

        $biz = $document->properties['biz'];
        self::assertInstanceOf(BoolTypeDocument::class, $biz->type);
        self::assertTrue($biz->default);
    }
}
