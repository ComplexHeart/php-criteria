<?php

declare(strict_types=1);

namespace ComplexHeart\Domain\Criteria;

use ComplexHeart\Contracts\Domain\Model\ValueObject;
use ComplexHeart\Domain\Model\Traits\IsValueObject;

/**
 * Class Filter
 *
 * @author Unay Santisteban <usantisteban@othercode.es>
 * @package ComplexHeart\Domain\Criteria
 */
final class Filter implements ValueObject
{
    use IsValueObject;

    /**
     * The filter field name.
     *
     * @var string
     */
    private string $field; // @phpstan-ignore-line

    /**
     * The filter operator.
     *
     * @var Operator
     */
    private Operator $operator; // @phpstan-ignore-line

    /**
     * The filter field value.
     *
     * @var mixed
     */
    private mixed $value; // @phpstan-ignore-line

    /**
     * Filter constructor.
     *
     * @param  string  $field
     * @param  Operator  $operator
     * @param  mixed  $value
     */
    public function __construct(string $field, Operator $operator, mixed $value)
    {
        $this->initialize(compact('field', 'operator', 'value'));
    }

    /**
     * Named constructor, create a new Filter object.
     *
     * @param  string  $field
     * @param  Operator  $operator
     * @param  mixed  $value
     *
     * @return Filter
     */
    public static function create(string $field, Operator $operator, mixed $value): self
    {
        return new self($field, $operator, $value);
    }

    public static function createFromArray(array $filter): self
    {
        // check if the array is indexed or associative.
        $isIndexed = fn($source): bool => ([] !== $source) && array_keys($source) === range(0, count($source) - 1);

        return ($isIndexed($filter))
            ? self::create($filter[0], Operator::make($filter[1]), $filter[2])
            : self::create($filter['field'], Operator::make($filter['operator']), $filter['value']);
    }

    public static function createEqual(string $field, $value): self
    {
        return self::create($field, Operator::equal(), $value);
    }

    public static function createNotEqual(string $field, $value): self
    {
        return self::create($field, Operator::notEqual(), $value);
    }

    public static function createGreaterThan(string $field, $value): self
    {
        return self::create($field, Operator::gt(), $value);
    }

    public static function createGreaterOrEqualThan(string $field, $value): self
    {
        return self::create($field, Operator::gte(), $value);
    }

    public static function createLessThan(string $field, $value): self
    {
        return self::create($field, Operator::lt(), $value);
    }

    public static function createLessOrEqualThan(string $field, $value): self
    {
        return self::create($field, Operator::lte(), $value);
    }

    public static function createIn(string $field, $value): self
    {
        return self::create($field, Operator::in(), $value);
    }

    public static function createNotIn(string $field, $value): self
    {
        return self::create($field, Operator::notIn(), $value);
    }

    public static function createLike(string $field, $value): self
    {
        return self::create($field, Operator::like(), $value);
    }

    /**
     * Retrieve the filter field value object.
     *
     * @return string
     */
    public function field(): string
    {
        return $this->field;
    }

    /**
     * Return the operator value.
     *
     * @return Operator
     */
    public function operator(): Operator
    {
        return $this->operator;
    }

    /**
     * Return the field value.
     *
     * @return mixed
     */
    public function value(): mixed
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s.%s.%s',
            $this->field(),
            $this->operator()->value,
            is_array($this->value())
                ? implode('|', $this->value())
                : $this->value()
        );
    }
}
