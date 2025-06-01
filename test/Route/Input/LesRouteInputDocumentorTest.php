<?php
declare(strict_types=1);

namespace LesDocumentorTest\Route\Input;

use PHPUnit\Framework\Attributes\CoversClass;
use LesDocumentor\Route\Attribute\DocHttpProxy;
use LesDocumentor\Route\Attribute\DocHttpResponse;
use LesDocumentor\Route\Attribute\DocInput;
use LesDocumentor\Route\Attribute\DocInputProvided;
use LesDocumentor\Type\ClassParametersTypeDocumentor;
use LesDocumentor\Type\Document\Composite\Key\ExactKey;
use LesDocumentor\Route\Input\LesRouteInputDocumentor;
use LesDocumentor\Type\Document\Composite\Property;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentorTest\Route\Stub\ClassProxyStub;
use LesValueObject\Composite\Content;
use LesValueObject\Composite\Paginate;
use LesValueObject\Number\Int\Date\MilliTimestamp;
use LesValueObject\Number\Int\Paginate\Page;
use LesValueObject\Number\Int\Paginate\PerPage;
use LesValueObject\String\Format\Resource\Identifier;
use LesValueObject\String\Format\Resource\Type;
use PHPUnit\Framework\TestCase;
use Throwable;

#[CoversClass(\LesDocumentor\Route\Input\LesRouteInputDocumentor::class)]
final class LesRouteInputDocumentorTest extends TestCase
{
    public function testInputAttr(): void
    {
        $handler = new
        #[DocInput(Paginate::class)]
        #[DocHttpResponse(code: 201)]
        class {
        };


        $documentor = new LesRouteInputDocumentor();
        $input = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'middleware' => $handler::class,
            ],
        );

        self::assertEquals(
            new CompositeTypeDocument(
                [
                    new Property(new ExactKey('perPage'), (new ClassParametersTypeDocumentor())->document(PerPage::class)),
                    new Property(new ExactKey('page'), (new ClassParametersTypeDocumentor())->document(Page::class)),
                ],
            ),
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

        $documentor = new LesRouteInputDocumentor();
        $input = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'middleware' => $handler::class,
                'deprecated' => 'test',
            ],
        );

        self::assertEquals(
            new CompositeTypeDocument(
                [new Property(new ExactKey('type'), (new ClassParametersTypeDocumentor())->document(Type::class))],
            ),
            $input,
        );
    }

    public function testProxyOptions(): void
    {
        $handler = new #[DocInputProvided(['fiz'])] class {
        };

        $documentor = new LesRouteInputDocumentor();
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
            new CompositeTypeDocument(
                [new Property(new ExactKey('type'), (new ClassParametersTypeDocumentor())->document(Type::class))],
            ),
            $input,
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

        $documentor = new LesRouteInputDocumentor();
        $input = $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'middleware' => $handler::class,
                'input' => $event::class,
            ],
        );

        self::assertEquals(
            new CompositeTypeDocument(
                [new Property(new ExactKey('page'), (new ClassParametersTypeDocumentor())->document(Page::class))],
            ),
            $input,
        );
    }

    public function testMissingAttribute(): void
    {
        $this->expectException(Throwable::class);

        $handler = new class {};

        $documentor = new LesRouteInputDocumentor();
        $documentor->document(
            [
                'path' => '/fiz/bar.foo',
                'resource' => 'bar',
                'middleware' => $handler::class,
            ],
        );
    }
}
