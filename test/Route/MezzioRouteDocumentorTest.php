<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route;

use LessDocumentor\Route\Attribute\DocHttpProxy;
use LessDocumentor\Route\Attribute\DocHttpResponse;
use LessDocumentor\Route\Attribute\DocInput;
use LessDocumentor\Route\Attribute\DocInputProvided;
use LessDocumentor\Route\Document\Property\Deprecated;
use LessDocumentor\Route\Document\Property\Method;
use LessDocumentor\Route\Document\Property\Response;
use LessDocumentor\Route\Document\Property\ResponseCode;
use LessDocumentor\Route\MezzioRouteDocumentor;
use LessDocumentor\Type\ObjectInputTypeDocumentor;
use LessDocumentor\Type\ObjectOutputTypeDocumentor;
use LessValueObject\Composite\Content;
use LessValueObject\Composite\Paginate;
use LessValueObject\Number\Int\Date\MilliTimestamp;
use LessValueObject\Number\Int\Paginate\Page;
use LessValueObject\Number\Int\Paginate\PerPage;
use LessValueObject\String\Format\Resource\Id;
use LessValueObject\String\Format\Resource\Type;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * @covers \LessDocumentor\Route\MezzioRouteDocumentor
 */
final class MezzioRouteDocumentorTest extends TestCase
{
    public function testProxyAttr(): void
    {
        $handler = new
        #[DocHttpProxy(ClassProxyStub::class, 'foo')]
        #[DocInputProvided(['fiz'])]
        class {
        };

        $documentor = new MezzioRouteDocumentor();
        $document = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'allowed_methods' => ['POST'],
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
            ['type' => (new ObjectInputTypeDocumentor())->document(Type::class)],
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

        $documentor = new MezzioRouteDocumentor();
        $document = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'allowed_methods' => ['POST'],
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
            ['type' => (new ObjectInputTypeDocumentor())->document(Type::class)],
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

    public function testEventOption(): void
    {
        $handler = new
        #[DocHttpResponse(Content::class, 201)]
        #[DocInputProvided(['id', 'on'])]
        class {
        };

        $id = new Id('35670141-bda3-460a-aa2b-3a1f868da8e0');
        $page = new Page(1);
        $on = MilliTimestamp::now();

        $event = new class ($id, $page, $on) {
            public function __construct(
                public Id $id,
                Page $page,
                MilliTimestamp $on,
            ) {}
        };

        $documentor = new MezzioRouteDocumentor();
        $document = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'allowed_methods' => ['POST'],
                'middleware' => $handler::class,
                'event' => $event::class,
            ],
        );

        self::assertSame(Method::Post, $document->getMethod());
        self::assertSame('/fiz/bar.foo', $document->getPath());
        self::assertSame('bar', $document->getResource());
        self::assertNull($document->getDeprecated());

        self::assertEquals(
            ['page' => (new ObjectInputTypeDocumentor())->document(Page::class)],
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

    public function testInputAttr(): void
    {
        $handler = new
        #[DocInput(Paginate::class)]
        #[DocHttpResponse(code: 201)]
        class {
        };


        $documentor = new MezzioRouteDocumentor();
        $document = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'allowed_methods' => ['POST'],
                'middleware' => $handler::class,
            ],
        );

        self::assertSame(Method::Post, $document->getMethod());
        self::assertSame('/fiz/bar.foo', $document->getPath());
        self::assertSame('bar', $document->getResource());
        self::assertNull($document->getDeprecated());

        self::assertEquals(
            [
                'perPage' => (new ObjectInputTypeDocumentor())->document(PerPage::class),
                'page' => (new ObjectInputTypeDocumentor())->document(Page::class),
            ],
            $document->getInput(),
        );
    }

    public function testMissingAttribute(): void
    {
        $this->expectException(Throwable::class);

        $handler = new class {};

        $documentor = new MezzioRouteDocumentor();
        $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'allowed_methods' => ['POST'],
                'middleware' => $handler::class,
            ],
        );
    }
}
