<?php

declare(strict_types=1);

namespace ComplexHeart\Domain\Criteria;

use ComplexHeart\Domain\Contracts\Model\ValueObject;
use ComplexHeart\Domain\Model\IsValueObject;

/**
 * Class Filter
 *
 * @author Unay Santisteban <usantisteban@othercode.io>
 * @package ComplexHeart\Domain\Criteria
 */
final class Filter implements ValueObject
{
    use IsValueObject;

    /**
     * Filter constructor.
     *
     * @param  string  $field
     * @param  Operator  $operator
     * @param  mixed  $value
     */
    public function __construct(
        private readonly string $field,
        private readonly Operator $operator,
        private readonly mixed $value,
    ) {
        $this->check();
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

    /**
     * @param  array<string, scalar>|array<string>  $filter
     * @return self
     */
    public static function createFromArray(array $filter): self
    {
        // check if the array is indexed or associative.
        $isIndexed = fn($source): bool => ([] !== $source) && array_keys($source) === range(0, count($source) - 1);

        return ($isIndexed($filter))
            ? self::create(
                "$filter[0]",
                Operator::make("$filter[1]"),
                $filter[2]
            )
            : self::create(
                "{$filter['field']}",
                Operator::make("{$filter['operator']}"),
                "{$filter['value']}"
            );
    }

    public static function createEqual(string $field, mixed $value): self
    {
        return self::create($field, Operator::EQUAL, $value);
    }

    public static function createNotEqual(string $field, mixed $value): self
    {
        return self::create($field, Operator::NOT_LIKE, $value);
    }

    public static function createGreaterThan(string $field, mixed $value): self
    {
        return self::create($field, Operator::GT, $value);
    }

    public static function createGreaterOrEqualThan(string $field, mixed $value): self
    {
        return self::create($field, Operator::GTE, $value);
    }

    public static function createLessThan(string $field, mixed $value): self
    {
        return self::create($field, Operator::LT, $value);
    }

    public static function createLessOrEqualThan(string $field, mixed $value): self
    {
        return self::create($field, Operator::LTE, $value);
    }

    public static function createIn(string $field, mixed $value): self
    {
        return self::create($field, Operator::IN, $value);
    }

    public static function createNotIn(string $field, mixed $value): self
    {
        return self::create($field, Operator::NOT_IN, $value);
    }

    public static function createLike(string $field, mixed $value): self
    {
        return self::create($field, Operator::LIKE, $value);
    }

    public static function createNotLike(string $field, mixed $value): self
    {
        return self::create($field, Operator::NOT_LIKE, $value);
    }

    public static function createContains(string $field, mixed $value): self
    {
        return self::create($field, Operator::CONTAINS, $value);
    }

    public static function createNotContains(string $field, mixed $value): self
    {
        return self::create($field, Operator::NOT_CONTAINS, $value);
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
