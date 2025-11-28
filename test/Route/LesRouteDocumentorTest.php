<?php

declare(strict_types=1);

namespace LesDocumentorTest\Route;

use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Route\Attribute\DocHttpProxy;
use LesDocumentor\Route\Attribute\DocHttpResponse;
use LesDocumentor\Route\Attribute\DocInputProvided;
use LesDocumentor\Route\Document\Property\Resource;
use LesDocumentor\Route\Document\Property\Deprecated;
use LesDocumentor\Route\Document\Property\Method;
use LesDocumentor\Route\Document\Property\Path;
use LesDocumentor\Route\Document\Property\Response;
use LesDocumentor\Type\ClassParametersTypeDocumentor;
use LesDocumentor\Type\ClassPropertiesTypeDocumentor;
use LesDocumentor\Route\Document\Property\ResponseCode;
use LesDocumentor\Route\LesRouteDocumentor;
use LesDocumentor\Type\Document\CollectionTypeDocument;
use LesDocumentor\Type\Document\Composite\Property;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentorTest\Route\Stub\ClassProxyStub;
use LesDocumentorTest\Route\Stub\ResourceStub;
use LesValueObject\Composite\Content;
use LesValueObject\Number\Int\Date\MilliTimestamp;
use LesValueObject\Number\Int\Paginate\Page;
use LesValueObject\String\Format\Resource\Identifier;
use LesValueObject\String\Format\Resource\Type;
use PHPUnit\Framework\TestCase;
use Throwable;
use LesDocumentor\Type\Document\Composite\Key\ExactKey;

#[CoversClass(LesRouteDocumentor::class)]
final class LesRouteDocumentorTest extends TestCase
{
    public function testProxyAttr(): void
    {
        $handler = new
        #[DocHttpProxy(ClassProxyStub::class, 'foo')]
        #[DocInputProvided(['fiz'])]
        class {
        };

        $documentor = new LesRouteDocumentor();
        $document = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'middleware' => $handler::class,
                'deprecated' => 'test',
                'method' => 'POST',
            ],
        );

        self::assertSame(Method::Post, $document->method);
        self::assertEquals(new Path('/fiz/bar.foo'), $document->path);
        self::assertEquals(new Resource('bar'), $document->resource);
        self::assertEquals(
            new Deprecated(null, 'test'),
            $document->deprecated
        );

        self::assertEquals(
            new CompositeTypeDocument(
                [
                    new Property(
                        new ExactKey('type'),
                        (new ClassParametersTypeDocumentor())->document(Type::class),
                    ),
                ],
            ),
            $document->input,
        );

        self::assertEquals(
            [
                new Response(
                    new ResponseCode(200),
                    (new ClassPropertiesTypeDocumentor())->document(Page::class),
                ),
            ],
            $document->responses,
        );
    }

    public function testProxyOptions(): void
    {
        $handler = new #[DocInputProvided(['fiz'])] class {
        };

        $documentor = new LesRouteDocumentor();
        $document = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'middleware' => $handler::class,
                'proxy' => [
                    'class' => ClassProxyStub::class,
                    'method' => 'foo',
                ],
                'alternate' => 'test',
                'method' => 'POST',
            ],
        );

        self::assertSame(Method::Post, $document->method);
        self::assertEquals(new Path('/fiz/bar.foo'), $document->path);
        self::assertEquals(new Resource('bar'), $document->resource);
        self::assertEquals(
            new Deprecated('test', null),
            $document->deprecated
        );

        self::assertEquals(
            new CompositeTypeDocument(
                [
                    new Property(
                        new ExactKey('type'),
                        (new ClassParametersTypeDocumentor())->document(Type::class),
                    ),
                ],
            ),
            $document->input,
        );

        self::assertEquals(
            [
                new Response(
                    new ResponseCode(200),
                    (new ClassPropertiesTypeDocumentor())->document(Page::class),
                ),
            ],
            $document->responses,
        );
    }

    public function testProxyOptionsResourceModel(): void
    {
        $handler = new class {
        };

        $documentor = new LesRouteDocumentor();
        $document = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'middleware' => $handler::class,
                'proxy' => [
                    'class' => ClassProxyStub::class,
                    'method' => 'bar',
                ],
                'method' => 'POST',
            ],
        );

        self::assertEquals(
            [
                new Response(
                    new ResponseCode(200),
                    (new ClassPropertiesTypeDocumentor())->document(ResourceStub::class),
                ),
            ],
            $document->responses,
        );
    }

    public function testProxyOptionsResourceSet(): void
    {
        $handler = new class {
        };

        $documentor = new LesRouteDocumentor();
        $document = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'middleware' => $handler::class,
                'proxy' => [
                    'class' => ClassProxyStub::class,
                    'method' => 'biz',
                ],
                'method' => 'POST',
            ],
        );

        self::assertEquals(
            [
                new Response(
                    new ResponseCode(200),
                    new CollectionTypeDocument(
                        (new ClassPropertiesTypeDocumentor())->document(ResourceStub::class),
                        null,
                        null,
                    ),
                ),
            ],
            $document->responses,
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
            ) {
            }
        };

        $documentor = new LesRouteDocumentor();
        $document = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'middleware' => $handler::class,
                'input' => $event::class,
                'method' => 'POST',
            ],
        );

        self::assertSame(Method::Post, $document->method);
        self::assertEquals(new Path('/fiz/bar.foo'), $document->path);
        self::assertEquals(new Resource('bar'), $document->resource);
        self::assertNull($document->deprecated);

        self::assertEquals(
            new CompositeTypeDocument(
                [
                    new Property(
                        new ExactKey('page'),
                        (new ClassParametersTypeDocumentor())->document(Page::class),
                    )
                ],
            ),
            $document->input,
        );

        self::assertEquals(
            [
                new Response(
                    new ResponseCode(201),
                    (new ClassPropertiesTypeDocumentor())->document(Content::class),
                ),
            ],
            $document->responses,
        );
    }

    public function testMissingAttribute(): void
    {
        $this->expectException(Throwable::class);

        $handler = new class {
        };

        $documentor = new LesRouteDocumentor();
        $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'middleware' => $handler::class,
                'method' => 'POST',
            ],
        );
    }
}
