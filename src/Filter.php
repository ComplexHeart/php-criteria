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
     * @param  scalar|null|array<scalar>  $value
     */
    public function __construct(
        private readonly string $field,
        private readonly Operator $operator,
        private readonly bool|float|int|string|null|array $value,
    ) {
        $this->check();
    }

    /**
     * Named constructor, create a new Filter object.
     *
     * @param  string  $field
     * @param  Operator  $operator
     * @param  scalar|null|array<scalar>  $value
     * @return Filter
     */
    public static function create(string $field, Operator $operator, bool|float|int|string|null|array $value): self
    {
        return new self($field, $operator, $value);
    }

    /**
     * @param  array<string, scalar>|array<string>  $filter
     * @return Filter
     */
    public static function fromArray(array $filter): self
    {
        // check if the array is indexed or associative.
        $isIndexed = fn ($source): bool => ([] !== $source) && array_keys($source) === range(0, count($source) - 1);

        return ($isIndexed($filter))
            ? self::create(
                "$filter[0]",
                Operator::create("$filter[1]"),
                $filter[2]
            )
            : self::create(
                "{$filter['field']}",
                Operator::create("{$filter['operator']}"),
                $filter['value']
            );
    }

    /**
     * @param  string  $field
     * @param  bool|float|int|string|null  $value
     * @return self
     */
    public static function equal(string $field, bool|float|int|string|null $value): self
    {
        return self::create($field, Operator::EQUAL, $value);
    }

    /**
     * @param  string  $field
     * @param  bool|float|int|string|null  $value
     * @return self
     */
    public static function notEqual(string $field, bool|float|int|string|null $value): self
    {
        return self::create($field, Operator::NOT_EQUAL, $value);
    }

    /**
     * @param  string  $field
     * @param  bool|float|int|string|null  $value
     * @return self
     */
    public static function greaterThan(string $field, bool|float|int|string|null $value): self
    {
        return self::create($field, Operator::GT, $value);
    }

    /**
     * @param  string  $field
     * @param  bool|float|int|string|null  $value
     * @return self
     */
    public static function greaterOrEqualThan(string $field, bool|float|int|string|null $value): self
    {
        return self::create($field, Operator::GTE, $value);
    }

    /**
     * @param  string  $field
     * @param  bool|float|int|string|null  $value
     * @return self
     */
    public static function lessThan(string $field, bool|float|int|string|null $value): self
    {
        return self::create($field, Operator::LT, $value);
    }

    /**
     * @param  string  $field
     * @param  bool|float|int|string|null  $value
     * @return self
     */
    public static function lessOrEqualThan(string $field, bool|float|int|string|null $value): self
    {
        return self::create($field, Operator::LTE, $value);
    }

    /**
     * @param  string  $field
     * @param  array<scalar>  $value
     * @return self
     */
    public static function in(string $field, array $value): self
    {
        return self::create($field, Operator::IN, $value);
    }

    /**
     * @param  string  $field
     * @param  array<scalar>  $value
     * @return self
     */
    public static function notIn(string $field, array $value): self
    {
        return self::create($field, Operator::NOT_IN, $value);
    }

    /**
     * @param  string  $field
     * @param  bool|float|int|string|null  $value
     * @return self
     */
    public static function like(string $field, bool|float|int|string|null $value): self
    {
        return self::create($field, Operator::LIKE, $value);
    }

    /**
     * @param  string  $field
     * @param  bool|float|int|string|null  $value
     * @return self
     */
    public static function notLike(string $field, bool|float|int|string|null $value): self
    {
        return self::create($field, Operator::NOT_LIKE, $value);
    }

    /**
     * @param  string  $field
     * @param  bool|float|int|string|null  $value
     * @return self
     */
    public static function contains(string $field, bool|float|int|string|null $value): self
    {
        return self::create($field, Operator::CONTAINS, $value);
    }

    /**
     * @param  string  $field
     * @param  bool|float|int|string|null  $value
     * @return self
     */
    public static function notContains(string $field, bool|float|int|string|null $value): self
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
     * @return scalar|null|array<scalar>
     */
    public function value(): bool|float|int|string|null|array
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
                ? implode(',', $this->value())
                : $this->value()
        );
    }
}
