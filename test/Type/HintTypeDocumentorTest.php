<?php
declare(strict_types=1);

namespace LessDocumentorTest\Type;

use ReflectionNamedType;
use ReflectionUnionType;
use LessDocumentor\Type\TypeDocumentor;
use LessDocumentor\Type\HintTypeDocumentor;
use PHPUnit\Framework\TestCase;
use LessDocumentor\Type\Document\TypeDocument;
use LessDocumentor\Type\Exception\UnexpectedInput;
use LessDocumentor\Type\Document\UnionTypeDocument;

/**
 * @covers \LessDocumentor\Type\HintTypeDocumentor
 */
class HintTypeDocumentorTest extends TestCase
{
    public function testNonSupported(): void
    {
        $this->expectException(UnexpectedInput::class);

        $classDocumentor = $this->createMock(TypeDocumentor::class);

        $documentor = new HintTypeDocumentor($classDocumentor);
        $documentor->document('fiz');
    }

    public function testBuiltinPass(): void
    {
        $classDocumentor = $this->createMock(TypeDocumentor::class);

        $expected = $this->createMock(TypeDocument::class);
        $expected->expects(self::never())->method('withNullable');

        $builtin = $this->createMock(ReflectionNamedType::class);
        $builtin->method('isBuiltin')->willReturn(true);
        $builtin->method('getName')->willReturn('fiz');

        $builtinDocumetor = $this->createMock(TypeDocumentor::class);
        $builtinDocumetor->expects(self::once())->method('document')->with('fiz')->willReturn($expected);

        $documentor = new HintTypeDocumentor($classDocumentor, $builtinDocumetor);

        self::assertSame($expected, $documentor->document($builtin));
    }

    public function testClassPass(): void
    {
        $expected = $this->createMock(TypeDocument::class);
        $expected->expects(self::never())->method('withNullable');

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('isBuiltin')->willReturn(false);
        $type->method('getName')->willReturn('fiz');

        $classDocumentor = $this->createMock(TypeDocumentor::class);
        $classDocumentor->expects(self::once())->method('document')->with('fiz')->willReturn($expected);

        $documentor = new HintTypeDocumentor($classDocumentor);

        self::assertSame($expected, $documentor->document($type));
    }

    public function testNullable(): void
    {
        $expected = $this->createMock(TypeDocument::class);
        $expected->expects(self::once())->method('withNullable')->willReturn($expected);

        $type = $this->createMock(ReflectionNamedType::class);
        $type->method('isBuiltin')->willReturn(false);
        $type->method('getName')->willReturn('fiz');
        $type->method('allowsNull')->willReturn(true);

        $classDocumentor = $this->createMock(TypeDocumentor::class);
        $classDocumentor->expects(self::once())->method('document')->with('fiz')->willReturn($expected);

        $documentor = new HintTypeDocumentor($classDocumentor);

        self::assertSame($expected, $documentor->document($type));
    }

    public function testUnionBuiltinPass(): void
    {
        $classDocumentor = $this->createMock(TypeDocumentor::class);

        $builintTypeDocumentFirst = $this->createMock(TypeDocument::class);
        $builintTypeDocumentSecond = $this->createMock(TypeDocument::class);

        $builtinFirst = $this->createMock(ReflectionNamedType::class);
        $builtinFirst->method('isBuiltin')->willReturn(true);
        $builtinFirst->method('getName')->willReturn('fiz');

        $builtinSecond = $this->createMock(ReflectionNamedType::class);
        $builtinSecond->method('isBuiltin')->willReturn(true);
        $builtinSecond->method('getName')->willReturn('sec');

        $union = $this->createMock(ReflectionUnionType::class);
        $union->method('getTypes')->willReturn([$builtinFirst, $builtinSecond]);

        $builtinDocumetor = $this->createMock(TypeDocumentor::class);
        $builtinDocumetor
            ->expects(self::exactly(2))
            ->method('document')
            ->willReturnMap(
                [
                    ['fiz', $builintTypeDocumentSecond],
                    ['sec', $builintTypeDocumentFirst],
                ],
            );

        $documentor = new HintTypeDocumentor($classDocumentor, $builtinDocumetor);

        self::assertEquals(
            new UnionTypeDocument(
                [
                    $builintTypeDocumentFirst,
                    $builintTypeDocumentSecond,
                ]
            ),
            $documentor->document($union),
        );
    }
}
