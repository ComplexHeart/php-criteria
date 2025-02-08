<?php

declare(strict_types=1);

namespace ComplexHeart\Domain\Criteria;

use ComplexHeart\Domain\Model\TypedCollection;

use function Lambdish\Phunctional\map;

/**
 * Class FilterGroup
 *
 * @author Unay Santisteban <usantisteban@othercode.io>
 * @package ComplexHeart\Domain\Criteria
 *
 * @extends TypedCollection<int, Filter>
 */
final class FilterGroup extends TypedCollection
{
    protected string $keyType = 'integer';

    protected string $valueType = Filter::class;

    /**
     * FilterGroup constructor.
     *
     * @param  array<int, Filter>  $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct(array_unique($items));
    }

    /**
     * @param  Filter  ...$filters
     * @return FilterGroup
     */
    public static function create(Filter ...$filters): self
    {
        return new self(array_values($filters));
    }

    /**
     * @param  array<int, array<int|string, mixed>>  $filters
     * @return FilterGroup
     */
    public static function fromArray(array $filters): self
    {
        return self::create(
            ...map(fn(array $filter): Filter => Filter::fromArray($filter), $filters)
        );
    }

    /**
     * Add new filter to the FilterGroup.
     *
     * @param  Filter  $new
     * @return FilterGroup
     */
    public function addFilter(Filter $new): self
    {
        if ($this->filter(fn(Filter $filter): bool => $filter->equals($new))->count() > 0) {
            return $this;
        }

        $this->items[] = $new;

        return $this;
    }

    /**
     * @param  string  $field
     * @param  bool|float|int|string|null  $value
     * @return self
     */
    public function addFilterEqual(string $field, mixed $value): self
    {
        return $this->addFilter(Filter::equal($field, $value));
    }

    /**
     * @param  string  $field
     * @param  bool|float|int|string|null  $value
     * @return self
     */
    public function addFilterNotEqual(string $field, mixed $value): self
    {
        return $this->addFilter(Filter::notEqual($field, $value));
    }

    /**
     * @param  string  $field
     * @param  bool|float|int|string|null  $value
     * @return self
     */
    public function addFilterGreaterThan(string $field, mixed $value): self
    {
        return $this->addFilter(Filter::greaterThan($field, $value));
    }

    /**
     * @param  string  $field
     * @param  bool|float|int|string|null  $value
     * @return self
     */
    public function addFilterGreaterOrEqualThan(string $field, mixed $value): self
    {
        return $this->addFilter(Filter::greaterOrEqualThan($field, $value));
    }

    /**
     * @param  string  $field
     * @param  bool|float|int|string|null  $value
     * @return self
     */
    public function addFilterLessThan(string $field, mixed $value): self
    {
        return $this->addFilter(Filter::lessThan($field, $value));
    }

    /**
     * @param  string  $field
     * @param  bool|float|int|string|null  $value
     * @return self
     */
    public function addFilterLessOrEqualThan(string $field, mixed $value): self
    {
        return $this->addFilter(Filter::lessOrEqualThan($field, $value));
    }

    /**
     * @param  string  $field
     * @param  array<scalar>  $value
     * @return FilterGroup
     */
    public function addFilterIn(string $field, array $value): self
    {
        return $this->addFilter(Filter::in($field, $value));
    }

    /**
     * @param  string  $field
     * @param  array<scalar>  $value
     * @return FilterGroup
     */
    public function addFilterNotIn(string $field, array $value): self
    {
        return $this->addFilter(Filter::notIn($field, $value));
    }

    /**
     * @param  string  $field
     * @param  string  $value
     * @return self
     */
    public function addFilterLike(string $field, string $value): self
    {
        return $this->addFilter(Filter::like($field, $value));
    }

    /**
     * @param  string  $field
     * @param  string  $value
     * @return self
     */
    public function addFilterNotLike(string $field, string $value): self
    {
        return $this->addFilter(Filter::notLike($field, $value));
    }

    /**
     * @param  string  $field
     * @param  string  $value
     * @return self
     */
    public function addFilterContains(string $field, string $value): self
    {
        return $this->addFilter(Filter::contains($field, $value));
    }

    /**
     * @param  string  $field
     * @param  string  $value
     * @return self
     */
    public function addFilterNotContains(string $field, string $value): self
    {
        return $this->addFilter(Filter::notContains($field, $value));
    }

    /**
     * Transform the FilterGroup to a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return join('+', map(fn(Filter $filter): string => $filter->__toString(), $this));
    }
}
