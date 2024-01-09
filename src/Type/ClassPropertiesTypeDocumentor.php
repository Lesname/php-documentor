<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use ReflectionClass;
use RuntimeException;
use ReflectionProperty;
use ReflectionException;
use LessDocumentor\Helper\AttributeHelper;
use LessDocumentor\Type\Document\TypeDocument;
use LessDocumentor\Type\Attribute\DocDeprecated;
use LessDocumentor\Type\Exception\UnexpectedInput;
use LessDocumentor\Type\Document\Composite\Property;
use LessDocumentor\Type\Document\CompositeTypeDocument;

final class ClassPropertiesTypeDocumentor extends AbstractClassTypeDocumentor
{
    private readonly TypeDocumentor $hintTypeDocumentor;

    public function __construct(?TypeDocumentor $hintTypeDocumentor = null)
    {
        $this->hintTypeDocumentor = $hintTypeDocumentor ?? new HintTypeDocumentor($this);
    }

    /**
     * @param class-string $class
     *
     * @throws ReflectionException
     * @throws UnexpectedInput
     */
    protected function documentObject(string $class): TypeDocument
    {
        $classReflection = new ReflectionClass($class);
        $properties = [];

        foreach ($classReflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $propertyType = $property->getType();

            if ($propertyType === null) {
                throw new RuntimeException("{$property->getName()} misses type information");
            }

            $properties[$property->getName()] = new Property(
                $this->hintTypeDocumentor->document($propertyType),
                deprecated: AttributeHelper::hasAttribute($property, DocDeprecated::class),
            );
        }

        return new CompositeTypeDocument($properties, reference: $class);
    }
}
