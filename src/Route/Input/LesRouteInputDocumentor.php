<?php

declare(strict_types=1);

namespace LesDocumentor\Route\Input;

use Override;
use LesDocumentor\Helper\AttributeHelper;
use LesValueObject\String\Exception\TooLong;
use LesValueObject\String\Exception\TooShort;
use LesDocumentor\Route\Attribute\DocHttpProxy;
use LesDocumentor\Route\Attribute\DocInput;
use LesDocumentor\Type\Exception\UnexpectedInput;
use LesDocumentor\Route\Attribute\DocInputProvided;
use LesDocumentor\Route\Exception\MissingAttribute;
use LesDocumentor\Type\Document\CompositeTypeDocument;
use LesDocumentor\Type\Document\TypeDocument;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use LesDocumentor\Type\ClassParametersTypeDocumentor;
use LesDocumentor\Type\MethodParametersTypeDocumentor;

final class LesRouteInputDocumentor implements RouteInputDocumentor
{
    /**
     * @param array<mixed> $route
     *
     * @throws TooLong
     * @throws TooShort
     * @throws UnexpectedInput
     * @throws MissingAttribute
     * @throws ReflectionException
     */
    #[Override]
    public function document(array $route): TypeDocument
    {
        assert(isset($route['middleware']) && is_string($route['middleware']) && class_exists($route['middleware']));

        $handler = new ReflectionClass($route['middleware']);

        if (AttributeHelper::hasAttribute($handler, DocInput::class)) {
            $document = $this->documentDocInput($handler);
        } elseif (isset($route['input'])) {
            assert(is_string($route['input']) && class_exists($route['input']));
            $document = $this->documentValueObject($route['input']);
        } else {
            if (isset($route['proxy'])) {
                assert(is_array($route['proxy']));
                $proxy = $route['proxy'];

                assert(is_string($proxy['class']) && (interface_exists($proxy['class']) || class_exists($proxy['class'])));
                assert(is_string($proxy['method']));

                $method = new ReflectionMethod($proxy['class'], $proxy['method']);
            } else {
                $attribute = AttributeHelper::getAttribute($handler, DocHttpProxy::class);
                $method = new ReflectionMethod($attribute->class, $attribute->method);
            }

            $document = (new MethodParametersTypeDocumentor())
                ->document($method);
        }

        assert($document instanceof CompositeTypeDocument);

        $properties = $document->properties;

        if (AttributeHelper::hasAttribute($handler, DocInputProvided::class)) {
            $attribute = AttributeHelper::getAttribute($handler, DocInputProvided::class);

            foreach ($attribute->keys as $key) {
                foreach ($properties as $index => $property) {
                    if ($property->key->matches($key)) {
                        unset($properties[$index]);
                    }
                }
            }

            // array values required to "reset" all keys
            $properties = array_values($properties);
        }

        return new CompositeTypeDocument($properties);
    }

    /**
     * @param ReflectionClass<object> $class
     *
     * @throws MissingAttribute
     */
    private function documentDocInput(ReflectionClass $class): CompositeTypeDocument
    {
        $attribute = AttributeHelper::getAttribute($class, DocInput::class);

        return $this->documentValueObject($attribute->input);
    }

    /**
     * @param class-string $valueObject
     *
     * @throws UnexpectedInput
     * @throws TooLong
     * @throws TooShort
     * @throws MissingAttribute
     * @throws ReflectionException
     */
    private function documentValueObject(string $valueObject): CompositeTypeDocument
    {
        $objInputDocumentor = new ClassParametersTypeDocumentor();

        $document = $objInputDocumentor->document($valueObject);
        assert($document instanceof CompositeTypeDocument);

        return $document;
    }
}
