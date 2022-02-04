<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type;

use LessDocumentor\Type\Document\CompositeTypeDocument;
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
        $perPage = new PerPage(12);
        $stub = EnumStub::Fiz;

        $composite = new class ($perPage, $stub) {
            public function __construct(
                public PerPage $perPage,
                private ?EnumStub $stub,
            ) {}
        };

        $documentor = new ObjectInputTypeDocumentor();
        $document = $documentor->document($composite::class);

        self::assertInstanceOf(CompositeTypeDocument::class, $document);
        self::assertTrue($document->isRequired());
        self::assertSame($composite::class, $document->getReference());
        self::assertNull($document->getDescription());
        self::assertNull($document->getDeprecated());

        self::assertSame(2, count($document->properties));

        $perPage = $document->properties['perPage'];
        self::assertSame(0, $perPage->range->minimal);
        self::assertSame(100, $perPage->range->maximal);
        self::assertTrue($perPage->isRequired());
        self::assertSame(PerPage::class, $perPage->getReference());
        self::assertNull($perPage->getDescription());
        self::assertNull($perPage->getDeprecated());

        $stub = $document->properties['stub'];
        self::assertSame(EnumStub::cases(), $stub->cases);
        self::assertFalse($stub->isRequired());
        self::assertSame(EnumStub::class, $stub->getReference());
        self::assertNull($stub->getDescription());
        self::assertNull($stub->getDeprecated());
    }
}
