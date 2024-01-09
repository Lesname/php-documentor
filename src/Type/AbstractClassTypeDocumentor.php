<?php
declare(strict_types=1);

namespace LessDocumentor\Type;

use BackedEnum;
use ReflectionClass;
use ReflectionException;
use LessValueObject\ValueObject;
use LessDocumentor\Helper\AttributeHelper;
use LessDocumentor\Type\Attribute\DocFormat;
use LessValueObject\String\Exception\TooLong;
use LessValueObject\String\StringValueObject;
use LessValueObject\Number\NumberValueObject;
use LessValueObject\String\Exception\TooShort;
use LessDocumentor\Type\Document\TypeDocument;
use LessDocumentor\Type\Document\Number\Range;
use LessDocumentor\Type\Document\String\Length;
use LessDocumentor\Type\Document\String\Pattern;
use LessDocumentor\Type\Document\Collection\Size;
use LessDocumentor\Type\Exception\UnexpectedInput;
use LessDocumentor\Type\Document\EnumTypeDocument;
use LessDocumentor\Route\Exception\MissingAttribute;
use LessDocumentor\Type\Document\NumberTypeDocument;
use LessDocumentor\Type\Document\StringTypeDocument;
use LessValueObject\Collection\CollectionValueObject;
use LessDocumentor\Type\Document\CompositeTypeDocument;
use LessDocumentor\Type\Document\CollectionTypeDocument;
use LessValueObject\Composite\DynamicCompositeValueObject;
use LessValueObject\String\Format\AbstractRegexStringFormatValueObject;

abstract class AbstractClassTypeDocumentor implements TypeDocumentor
{
    /**
     * @psalm-assert-if-true class-string $input
     */
    public function canDocument(mixed $input): bool
    {
        return is_string($input) && class_exists($input);
    }

    /**
     * @throws MissingAttribute
     * @throws ReflectionException
     * @throws TooLong
     * @throws TooShort
     * @throws UnexpectedInput
     */
    public function document(mixed $input): TypeDocument
    {
        if (!$this->canDocument($input)) {
            throw new UnexpectedInput('class-string', $input);
        }

        if ($input === DynamicCompositeValueObject::class) {
            return new CompositeTypeDocument([], true);
        }

        return match (true) {
            is_subclass_of($input, StringValueObject::class) => $this->documentStringValueObject($input),
            is_subclass_of($input, NumberValueObject::class) => $this->documentNumberValueObject($input),
            is_subclass_of($input, CollectionValueObject::class) => $this->documentCollectionValueObject($input),
            is_subclass_of($input, BackedEnum::class) => $this->documentEnum($input),
            default => $this->documentObject($input),
        };
    }

    /**
     * @param class-string<CollectionValueObject<ValueObject>> $class
     *
     * @throws TooLong
     * @throws TooShort
     * @throws UnexpectedInput
     * @throws MissingAttribute
     * @throws ReflectionException
     */
    protected function documentCollectionValueObject(string $class): TypeDocument
    {
        return new CollectionTypeDocument(
            $this->document($class::getItemType()),
            new Size(
                $class::getMinimumSize(),
                $class::getMaximumSize(),
            ),
            $class,
        );
    }

    /**
     * @param class-string<BackedEnum> $class
     */
    protected function documentEnum(string $class): TypeDocument
    {
        return new EnumTypeDocument(
            array_map(
                static fn (BackedEnum $enum): string => (string)$enum->value,
                $class::cases(),
            ),
            $class,
        );
    }

    /**
     * @param class-string<NumberValueObject> $class
     *
     * @throws MissingAttribute
     * @throws ReflectionException
     */
    protected function documentNumberValueObject(string $class): TypeDocument
    {
        $refClass = new ReflectionClass($class);

        $format = AttributeHelper::hasAttribute($refClass, DocFormat::class)
            ? AttributeHelper::getAttribute($refClass, DocFormat::class)->name
            : null;

        return new NumberTypeDocument(
            new Range(
                $class::getMinimumValue(),
                $class::getMaximumValue(),
            ),
            $class::getMultipleOf(),
            $format,
            $class,
        );
    }

    /**
     * @param class-string<StringValueObject> $class
     *
     * @return TypeDocument
     * @throws TooLong
     * @throws TooShort
     * @throws MissingAttribute
     * @throws ReflectionException
     */
    protected function documentStringValueObject(string $class): TypeDocument
    {
        $refClass = new ReflectionClass($class);

        if (AttributeHelper::hasAttribute($refClass, DocFormat::class)) {
            $format = AttributeHelper::getAttribute($refClass, DocFormat::class)->name;
        } else {
            $format = null;
        }

        $pattern = is_subclass_of($class, AbstractRegexStringFormatValueObject::class)
            ? new Pattern($class::getRegularExpression())
            : null;

        return new StringTypeDocument(
            new Length($class::getMinimumLength(), $class::getMaximumLength()),
            $format,
            $pattern,
            $class,
        );
    }

    /**
     * @param class-string $class
     */
    abstract protected function documentObject(string $class): TypeDocument;
}
