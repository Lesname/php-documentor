<?php
declare(strict_types=1);

namespace LesDocumentor\Route;

use Override;
use LesValueObject\Composite\Paginate;
use LesDocumentor\Helper\AttributeHelper;
use LesValueObject\String\Exception\TooLong;
use LesValueObject\String\Exception\TooShort;
use LesDocumentor\Route\Attribute\DocHttpProxy;
use LesValueObject\Number\Int\Paginate\PerPage;
use LesDocumentor\Route\Document\Property\Method;
use LesDocumentor\Route\Exception\UnknownBuiltin;
use LesDocumentor\Type\Exception\UnexpectedInput;
use LesDocumentor\Route\Attribute\DocHttpResponse;
use LesDocumentor\Route\Attribute\DocResource;
use LesDocumentor\Route\Document\Property\Resource;
use LesDocumentor\Route\Document\Property\Deprecated;
use LesDocumentor\Route\Document\Property\Path;
use LesDocumentor\Route\Document\Property\Response;
use LesDocumentor\Type\ClassPropertiesTypeDocumentor;
use LesDocumentor\Route\Input\LesRouteInputDocumentor;
use LesDocumentor\Route\Document\Property\ResponseCode;
use LesDocumentor\Route\Document\RouteDocument;
use LesDocumentor\Route\Exception\MissingAttribute;
use LesDocumentor\Route\Input\RouteInputDocumentor;
use LesDocumentor\Type\Document\AnyTypeDocument;
use LesDocumentor\Type\Document\BoolTypeDocument;
use LesDocumentor\Type\Document\Collection\Size;
use LesDocumentor\Type\Document\CollectionTypeDocument;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\NumberTypeDocument;
use LesDocumentor\Type\Document\StringTypeDocument;
use LesDocumentor\Type\Document\Wrapper\Attribute\DocTypeWrapper;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;
use Traversable;
use LesDocumentor\Route\Document\Property\Exception\InvalidResponseCode;

final class LesRouteDocumentor implements RouteDocumentor
{
    private ?RouteInputDocumentor $routeInputDocumentor = null;

    /**
     * @param array<mixed> $route
     *
     * @return RouteDocument
     * @throws TooLong
     * @throws TooShort
     * @throws MissingAttribute
     * @throws ReflectionException
     */
    #[Override]
    public function document(array $route): RouteDocument
    {
        assert(isset($route['path']) && is_string($route['path']));
        assert(isset($route['resource']) && is_string($route['resource']));
        assert(isset($route['method']) && is_string($route['method']));

        $deprecated = null;

        $routeAlternate = $route['alternate'] ?? null;
        assert($routeAlternate === null || is_string($routeAlternate));

        $routeDeprecated = $route['deprecated'] ?? null;
        assert($routeDeprecated === null || is_string($routeDeprecated));

        if (is_string($routeAlternate) || is_string($routeDeprecated)) {
            $deprecated = new Deprecated($routeAlternate, $routeDeprecated);
        }

        return new RouteDocument(
            Method::from(strtolower($route['method'])),
            new Path($route['path']),
            new Resource($route['resource']),
            $deprecated,
            $this->getRouteInputDocumentor()->document($route),
            $this->documentResponses($route),
        );
    }

    /**
     * @return RouteInputDocumentor
     */
    public function getRouteInputDocumentor(): RouteInputDocumentor
    {
        return $this->routeInputDocumentor ??= new LesRouteInputDocumentor();
    }

    /**
     * @param array<mixed> $route
     *
     * @return array<int, Response>
     *
     * @throws ReflectionException
     * @throws TooLong
     * @throws TooShort
     * @throws UnknownBuiltin
     * @throws UnexpectedInput
     * @throws InvalidResponseCode
     * @throws MissingAttribute
     */
    private function documentResponses(array $route): array
    {
        assert(isset($route['middleware']) && is_string($route['middleware']) && class_exists($route['middleware']));

        $classPropertiesTypeDocumentor = new ClassPropertiesTypeDocumentor();

        $handler = new ReflectionClass($route['middleware']);

        if (AttributeHelper::hasAttribute($handler, DocHttpResponse::class)) {
            $response = AttributeHelper::getAttribute($handler, DocHttpResponse::class);

            return [
                new Response(
                    new ResponseCode($response->code),
                    $response->output
                        ? $classPropertiesTypeDocumentor->document($response->output)
                        : null,
                ),
            ];
        }

        if (isset($route['proxy'])) {
            assert(is_array($route['proxy']));
            $proxy = $route['proxy'];

            assert(is_string($proxy['class']) && (interface_exists($proxy['class']) || class_exists($proxy['class'])));
            assert(is_string($proxy['method']));

            $proxyClass = new ReflectionClass($proxy['class']);
            $proxyMethod = $proxyClass->getMethod($proxy['method']);

            $return = $proxyMethod->getReturnType();
            assert($return instanceof ReflectionNamedType);

            if (is_subclass_of($return->getName(), Traversable::class) || $return->getName() === 'array') {
                $hasPaginate = false;

                foreach ($proxyMethod->getParameters() as $parameter) {
                    $type = $parameter->getType();

                    if ($type instanceof ReflectionNamedType && $type->getName() === Paginate::class) {
                        $hasPaginate = true;

                        break;
                    }
                }

                $attribute = AttributeHelper::hasAttribute($proxyMethod, DocResource::class)
                    ? AttributeHelper::getAttribute($proxyMethod, DocResource::class)
                    : AttributeHelper::getAttribute($proxyClass, DocResource::class);

                $output = new CollectionTypeDocument(
                    $classPropertiesTypeDocumentor->document($attribute->resource),
                    $hasPaginate ? new Size(max(0, (int)floor(PerPage::getMinimumValue())), (int)ceil(PerPage::getMaximumValue())) : null,
                    null,
                );
            } elseif (interface_exists($return->getName())) {
                $attribute = AttributeHelper::getAttribute($proxyClass, DocResource::class);
                $output = $classPropertiesTypeDocumentor->document($attribute->resource);
            } else {
                $returns = $return->getName();

                if (class_exists($returns)) {
                    $output = $classPropertiesTypeDocumentor->document($returns);
                } else {
                    $output = match ($returns) {
                        'array' => new CompositeTypeDocument([], true),
                        'bool' => new BoolTypeDocument(),
                        'float' => new NumberTypeDocument(null, null, null),
                        'int' => new NumberTypeDocument(null, 1),
                        'mixed' => new AnyTypeDocument(),
                        'string' => new StringTypeDocument(null),
                        default => throw new UnknownBuiltin($returns),
                    };
                }
            }
        } else {
            $attribute = AttributeHelper::getAttribute($handler, DocHttpProxy::class);
            $method = new ReflectionMethod($attribute->class, $attribute->method);

            $return = $method->getReturnType();
            assert($return instanceof ReflectionNamedType);
            assert($return->isBuiltin() === false);

            if (interface_exists($return->getName())) {
                $iterable = is_subclass_of($return->getName(), Traversable::class);
                $class = AttributeHelper::getAttribute(
                    new ReflectionClass($attribute->class),
                    DocResource::class,
                )->resource;
            } else {
                $iterable = false;
                $class = $return->getName();
                assert(class_exists($class));
            }

            $output = $classPropertiesTypeDocumentor->document($class);

            if ($iterable) {
                $output = new CollectionTypeDocument($output, null);
            }
        }

        if (AttributeHelper::hasAttribute($handler, DocTypeWrapper::class)) {
            $attribute = AttributeHelper::getAttribute($handler, DocTypeWrapper::class);
            $wrapper = new $attribute->typeWrapper();

            $output = $wrapper->wrap($output);
        }

        return [new Response(new ResponseCode(200), $output)];
    }
}
