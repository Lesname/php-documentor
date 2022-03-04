<?php
declare(strict_types=1);

namespace LessDocumentorTest\Route\Input;

use LessDocumentor\Route\Attribute\DocHttpProxy;
use LessDocumentor\Route\Attribute\DocHttpResponse;
use LessDocumentor\Route\Attribute\DocInput;
use LessDocumentor\Route\Attribute\DocInputProvided;
use LessDocumentor\Route\Input\MezzioRouteInputDocumentor;
use LessDocumentor\Type\ObjectInputTypeDocumentor;
use LessDocumentorTest\Route\ClassProxyStub;
use LessValueObject\Composite\Content;
use LessValueObject\Composite\Paginate;
use LessValueObject\Number\Int\Date\MilliTimestamp;
use LessValueObject\Number\Int\Paginate\Page;
use LessValueObject\Number\Int\Paginate\PerPage;
use LessValueObject\String\Format\Resource\Identifier;
use LessValueObject\String\Format\Resource\Type;
use PHPUnit\Framework\TestCase;
use Throwable;

/**
 * @covers \LessDocumentor\Route\Input\MezzioRouteInputDocumentor
 */
final class MezzioRouteInputDocumentorTest extends TestCase
{
    public function testInputAttr(): void
    {
        $handler = new
        #[DocInput(Paginate::class)]
        #[DocHttpResponse(code: 201)]
        class {
        };


        $documentor = new MezzioRouteInputDocumentor();
        $input = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'middleware' => $handler::class,
            ],
        );

        self::assertEquals(
            [
                'perPage' => (new ObjectInputTypeDocumentor())->document(PerPage::class),
                'page' => (new ObjectInputTypeDocumentor())->document(Page::class),
            ],
            $input,
        );
    }

    public function testProxyAttr(): void
    {
        $handler = new
        #[DocHttpProxy(ClassProxyStub::class, 'foo')]
        #[DocInputProvided(['fiz'])]
        class {
        };

        $documentor = new MezzioRouteInputDocumentor();
        $input = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'middleware' => $handler::class,
                'deprecated' => 'test',
            ],
        );

        self::assertEquals(
            ['type' => (new ObjectInputTypeDocumentor())->document(Type::class)],
            $input,
        );
    }

    public function testProxyOptions(): void
    {
        $handler = new #[DocInputProvided(['fiz'])] class {
        };

        $documentor = new MezzioRouteInputDocumentor();
        $input = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'middleware' => $handler::class,
                'proxy' => [
                    'class' => ClassProxyStub::class,
                    'method' => 'foo',
                ],
                'alternate' => 'test',
            ],
        );

        self::assertEquals(
            ['type' => (new ObjectInputTypeDocumentor())->document(Type::class)],
            $input,
        );
    }

    public function testEventOption(): void
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

        $documentor = new MezzioRouteInputDocumentor();
        $input = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'middleware' => $handler::class,
                'event' => $event::class,
            ],
        );

        self::assertEquals(
            ['page' => (new ObjectInputTypeDocumentor())->document(Page::class)],
            $input,
        );
    }

    public function testMissingAttribute(): void
    {
        $this->expectException(Throwable::class);

        $handler = new class {};

        $documentor = new MezzioRouteInputDocumentor();
        $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'middleware' => $handler::class,
            ],
        );
    }
}
