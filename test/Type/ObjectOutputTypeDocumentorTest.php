<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type;

use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\ObjectOutputTypeDocumentor;
use LessValueObject\Composite\AbstractCompositeValueObject;
use LessValueObject\Number\Int\Paginate\PerPage;
use PHPUnit\Framework\TestCase;

/**
 * @covers \LessDocumentor\Type\ObjectOutputTypeDocumentor
 */
final class ObjectOutputTypeDocumentorTest extends TestCase
{
    public function testCompositeValueObject(): void
    {
        $perPage = new PerPage(12);
        $stub = EnumStub::from('fiz');

        $composite = new class ($perPage, $stub, 1) extends AbstractCompositeValueObject {
            public function __construct(
                public PerPage $perPage,
                public ?EnumStub $stub,
                private int $foo,
            ) {}
        };

        $documentor = new ObjectOutputTypeDocumentor();
        $document = $documentor->document($composite::class);

        self::assertInstanceOf(CompositeTypeDocument::class, $document);
        self::assertTrue($document->isRequired());
        self::assertSame(EnumStub::class, $document->getReference());
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
        self::assertSame(['foo', 'fiz'], $stub->cases);
        self::assertFalse($stub->isRequired());
        self::assertSame(EnumStub::class, $stub->getReference());
        self::assertNull($stub->getDescription());
        self::assertNull($stub->getDeprecated());
    }
}
