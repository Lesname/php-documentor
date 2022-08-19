<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route;

use LessDocumentor\Route\Attribute\DocHttpProxy;
use LessDocumentor\Route\Attribute\DocHttpResponse;
use LessDocumentor\Route\Attribute\DocInputProvided;
use LessDocumentor\Route\Document\Property\Category;
use LessDocumentor\Route\Document\Property\Deprecated;
use LessDocumentor\Route\Document\Property\Method;
use LessDocumentor\Route\Document\Property\Response;
use LessDocumentor\Route\Document\Property\ResponseCode;
use LessDocumentor\Route\LessRouteDocumentor;
use LessDocumentor\Type\Document\CollectionTypeDocument;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\Property\Length;
use LessDocumentor\Type\ObjectInputTypeDocumentor;
use LessDocumentor\Type\ObjectOutputTypeDocumentor;
use LessDocumentorTest\Route\Stub\ClassProxyStub;
use LessDocumentorTest\Route\Stub\ResourceStub;
use LessValueObject\Composite\Content;
use LessValueObject\Number\Int\Date\MilliTimestamp;
use LessValueObject\Number\Int\Paginate\Page;
use LessValueObject\String\Format\Resource\Identifier;
use LessValueObject\String\Format\Resource\Type;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * @covers \LessDocumentor\Route\LessRouteDocumentor
 */
final class LessRouteDocumentorTest extends TestCase
{
    public function testProxyAttr(): void
    {
        $handler = new
        #[DocHttpProxy(ClassProxyStub::class, 'foo')]
        #[DocInputProvided(['fiz'])]
        class {
        };

        $documentor = new LessRouteDocumentor();
        $document = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'category' => Category::Query,
                'middleware' => $handler::class,
                'deprecated' => 'test',
            ],
        );

        self::assertSame(Method::Post, $document->getMethod());
        self::assertSame('/fiz/bar.foo', $document->getPath());
        self::assertSame('bar', $document->getResource());
        self::assertEquals(
            new Deprecated(null, 'test'),
            $document->getDeprecated()
        );

        self::assertEquals(
            new CompositeTypeDocument(
                ['type' => (new ObjectInputTypeDocumentor())->document(Type::class)],
                ['type'],
            ),
            $document->getInput(),
        );

        self::assertEquals(
            [
                new Response(
                    new ResponseCode(200),
                    (new ObjectOutputTypeDocumentor())->document(Page::class),
                ),
            ],
            $document->getRespones(),
        );
    }

    public function testProxyOptions(): void
    {
        $handler = new #[DocInputProvided(['fiz'])] class {
        };

        $documentor = new LessRouteDocumentor();
        $document = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'category' => Category::Query,
                'middleware' => $handler::class,
                'proxy' => [
                    'class' => ClassProxyStub::class,
                    'method' => 'foo',
                ],
                'alternate' => 'test',
            ],
        );

        self::assertSame(Method::Post, $document->getMethod());
        self::assertSame('/fiz/bar.foo', $document->getPath());
        self::assertSame('bar', $document->getResource());
        self::assertEquals(
            new Deprecated('test', null),
            $document->getDeprecated()
        );

        self::assertEquals(
            new CompositeTypeDocument(
                ['type' => (new ObjectInputTypeDocumentor())->document(Type::class)],
                ['type'],
            ),
            $document->getInput(),
        );

        self::assertEquals(
            [
                new Response(
                    new ResponseCode(200),
                    (new ObjectOutputTypeDocumentor())->document(Page::class),
                ),
            ],
            $document->getRespones(),
        );
    }

    public function testProxyOptionsResourceModel(): void
    {
        $handler = new class {
        };

        $documentor = new LessRouteDocumentor();
        $document = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'category' => Category::Query,
                'resource' => 'bar',
                'middleware' => $handler::class,
                'proxy' => [
                    'class' => ClassProxyStub::class,
                    'method' => 'bar',
                ],
            ],
        );

        self::assertEquals(
            [
                new Response(
                    new ResponseCode(200),
                    (new ObjectOutputTypeDocumentor())->document(ResourceStub::class),
                ),
            ],
            $document->getRespones(),
        );
    }

    public function testProxyOptionsResourceSet(): void
    {
        $handler = new class {
        };

        $documentor = new LessRouteDocumentor();
        $document = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'category' => Category::Query,
                'resource' => 'bar',
                'middleware' => $handler::class,
                'proxy' => [
                    'class' => ClassProxyStub::class,
                    'method' => 'biz',
                ],
            ],
        );

        self::assertEquals(
            [
                new Response(
                    new ResponseCode(200),
                    new CollectionTypeDocument(
                        (new ObjectOutputTypeDocumentor())->document(ResourceStub::class),
                        new Length(0, 100),
                        null,
                    ),
                ),
            ],
            $document->getRespones(),
        );
    }

    public function testInputOption(): void
    {
        $handler = new
        #[DocHttpResponse(Content::class, 201)]
        #[DocInputProvided(['id', 'on'])]
        class {
        };

        $id = new Identifier('35670141-bda3-460a-aa2b-3a1f868da8e0');
        $page = new Page(1);
        $on = MilliTimestamp::now();

        $event = new class ($id, $page, $on) {
            public function __construct(
                public Identifier $id,
                Page $page,
                MilliTimestamp $on,
            ) {}
        };

        $documentor = new LessRouteDocumentor();
        $document = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'category' => Category::Query,
                'resource' => 'bar',
                'middleware' => $handler::class,
                'input' => $event::class,
            ],
        );

        self::assertSame(Method::Post, $document->getMethod());
        self::assertSame('/fiz/bar.foo', $document->getPath());
        self::assertSame('bar', $document->getResource());
        self::assertNull($document->getDeprecated());

        self::assertEquals(
            new CompositeTypeDocument(
                ['page' => (new ObjectInputTypeDocumentor())->document(Page::class)],
                ['page'],
            ),
            $document->getInput(),
        );

        self::assertEquals(
            [
                new Response(
                    new ResponseCode(201),
                    (new ObjectOutputTypeDocumentor())->document(Content::class),
                ),
            ],
            $document->getRespones(),
        );
    }

    public function testMissingAttribute(): void
    {
        $this->expectException(Throwable::class);

        $handler = new class {};

        $documentor = new LessRouteDocumentor();
        $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'category' => Category::Query,
                'resource' => 'bar',
                'middleware' => $handler::class,
            ],
        );
    }
}
