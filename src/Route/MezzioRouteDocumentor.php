<?php
declare(strict_types=1);

namespace LessDocumentor\Route;

use LessDocumentor\Route\Attribute\DocHttpProxy;
use LessDocumentor\Route\Attribute\DocHttpResponse;
use LessDocumentor\Route\Attribute\DocInputProvided;
use LessDocumentor\Route\Document\PostRouteDocument;
use LessDocumentor\Route\Document\Property\Deprecated;
use LessDocumentor\Route\Document\Property\Response;
use LessDocumentor\Route\Document\Property\ResponseCode;
use LessDocumentor\Route\Document\RouteDocument;
use LessDocumentor\Route\Exception\MissingAttribute;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\TypeDocument;
use LessDocumentor\Type\ObjectInputTypeDocumentor;
use LessDocumentor\Type\ObjectOutputTypeDocumentor;
use LessValueObject\Number\Exception\MaxOutBounds;
use LessValueObject\Number\Exception\MinOutBounds;
use LessValueObject\Number\Exception\PrecisionOutBounds;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionNamedType;

final class MezzioRouteDocumentor implements RouteDocumentor
{
    /**
     * @param array<mixed> $route
     *
     * @throws MissingAttribute
     * @throws MaxOutBounds
     * @throws MinOutBounds
     * @throws PrecisionOutBounds
     * @throws ReflectionException
     */
    public function document(array $route): RouteDocument
    {
        assert(isset($route['path']) && is_string($route['path']));
        assert(isset($route['resource']) && is_string($route['resource']));

        assert(isset($route['allowed_methods']) && is_array($route['allowed_methods']));
        assert($route['allowed_methods'] === ['POST'], 'Currently only post methods supported');

        $deprecated = null;

        if (isset($route['alternate']) || isset($route['deprecated'])) {
            assert(!isset($route['deprecated']) || is_string($route['deprecated']));
            assert(!isset($route['alternate']) || is_string($route['alternate']));

            $deprecated = new Deprecated(
                $route['alternate'] ?? null,
                $route['deprecated'] ?? null,
            );
        }

        return new PostRouteDocument(
            $route['path'],
            $route['resource'],
            $deprecated,
            $this->documentInput($route),
            $this->documentResponses($route),
        );
    }

    /**
     * @param array<mixed> $route
     *
     * @return array<string, TypeDocument>
     *
     * @throws MissingAttribute
     * @throws MaxOutBounds
     * @throws MinOutBounds
     * @throws PrecisionOutBounds
     * @throws ReflectionException
     */
    private function documentInput(array $route): array
    {
        assert(isset($route['middleware']) && is_string($route['middleware']) && class_exists($route['middleware']));

        $handler = new ReflectionClass($route['middleware']);

        $objInputDocumentor = new ObjectInputTypeDocumentor();
        $input = [];

        if (isset($route['event'])) {
            assert(is_string($route['event']) && class_exists($route['event']));

            $document = $objInputDocumentor->document($route['event']);
            assert($document instanceof CompositeTypeDocument);

            $input = $document->properties;
        } else {
            if (isset($route['proxy'])) {
                assert(is_array($route['proxy']));
                $proxy = $route['proxy'];

                assert(is_string($proxy['class']) && class_exists($proxy['class']));
                assert(is_string($proxy['method']));

                $method = new ReflectionMethod($proxy['class'], $proxy['method']);
            } else {
                $attribute = $this->getAttribute($handler, DocHttpProxy::class);
                $method = new ReflectionMethod($attribute->class, $attribute->method);
            }

            foreach ($method->getParameters() as $parameter) {
                $type = $parameter->getType();
                assert($type instanceof ReflectionNamedType);
                assert($type->isBuiltin() === false);

                $class = $type->getName();
                assert(class_exists($class));

                $input[$parameter->getName()] = $objInputDocumentor
                    ->document($class)
                    ->withRequired(!$type->allowsNull());
            }
        }

        if ($this->hasAttribute($handler, DocInputProvided::class)) {
            $attribute = $this->getAttribute($handler, DocInputProvided::class);

            foreach ($attribute->keys as $key) {
                unset($input[$key]);
            }
        }

        return $input;
    }

    /**
     * @param array<mixed> $route
     *
     * @return array<int, Response>
     *
     * @throws MissingAttribute
     * @throws MaxOutBounds
     * @throws MinOutBounds
     * @throws PrecisionOutBounds
     * @throws ReflectionException
     */
    private function documentResponses(array $route): array
    {
        assert(isset($route['middleware']) && is_string($route['middleware']) && class_exists($route['middleware']));

        $objInputDocumentor = new ObjectOutputTypeDocumentor();

        $handler = new ReflectionClass($route['middleware']);

        if ($this->hasAttribute($handler, DocHttpResponse::class)) {
            $response = $this->getAttribute($handler, DocHttpResponse::class);

            return [
                new Response(
                    new ResponseCode($response->code),
                    $response->output
                        ? $objInputDocumentor->document($response->output)
                        : null,
                ),
            ];
        }

        if (isset($route['proxy'])) {
            assert(is_array($route['proxy']));
            $proxy = $route['proxy'];

            assert(is_string($proxy['class']) && class_exists($proxy['class']));
            assert(is_string($proxy['method']));

            $method = new ReflectionMethod($proxy['class'], $proxy['method']);
        } else {
            $attribute = $this->getAttribute($handler, DocHttpProxy::class);
            $method = new ReflectionMethod($attribute->class, $attribute->method);
        }

        $return = $method->getReturnType();
        assert($return instanceof ReflectionNamedType);
        assert($return->isBuiltin() === false);

        $class = $return->getName();
        assert(class_exists($class));

        return [
            new Response(
                new ResponseCode(200),
                $objInputDocumentor->document($class),
            ),
        ];
    }

    /**
     * @param ReflectionClass<object> $reflector
     * @param class-string<T> $nameAttribute
     *
     * @template T
     *
     * @return iterable<T>
     */
    private function getAttributes(ReflectionClass $reflector, string $nameAttribute): iterable
    {
        return array_map(
            static fn (ReflectionAttribute $attribute) => $attribute->newInstance(),
            $reflector->getAttributes($nameAttribute)
        );
    }

    /**
     * @param ReflectionClass<object> $reflector
     * @param class-string<T> $nameAttribute
     *
     * @template T
     *
     * @return T
     *
     * @throws MissingAttribute
     */
    private function getAttribute(ReflectionClass $reflector, string $nameAttribute)
    {
        foreach ($this->getAttributes($reflector, $nameAttribute) as $attribute) {
            return $attribute;
        }

        throw new MissingAttribute((string)$reflector, $nameAttribute);
    }

    /**
     * @param ReflectionClass<object> $reflector
     * @param class-string $nameAttribute
     */
    private function hasAttribute(ReflectionClass $reflector, string $nameAttribute): bool
    {
        return count($reflector->getAttributes($nameAttribute)) > 0;
    }
}
