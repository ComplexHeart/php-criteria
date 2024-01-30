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

    public function addFilterEqual(string $field, mixed $value): self
    {
        return $this->addFilter(Filter::equal($field, $value));
    }

    public function addFilterNotEqual(string $field, mixed $value): self
    {
        return $this->addFilter(Filter::notEqual($field, $value));
    }

    public function addFilterGreaterThan(string $field, mixed $value): self
    {
        return $this->addFilter(Filter::greaterThan($field, $value));
    }

    public function addFilterGreaterOrEqualThan(string $field, mixed $value): self
    {
        return $this->addFilter(Filter::greaterOrEqualThan($field, $value));
    }

    public function addFilterLessThan(string $field, mixed $value): self
    {
        return $this->addFilter(Filter::lessThan($field, $value));
    }

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

    public function addFilterLike(string $field, string $value): self
    {
        return $this->addFilter(Filter::like($field, $value));
    }

    public function addFilterNotLike(string $field, string $value): self
    {
        return $this->addFilter(Filter::notLike($field, $value));
    }

    public function addFilterContains(string $field, string $value): self
    {
        return $this->addFilter(Filter::contains($field, $value));
    }

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
