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
    public static function createFromArray(array $filters): self
    {
        return self::create(
            ...map(fn(array $filter): Filter => Filter::createFromArray($filter), $filters)
        );
    }

    /**
     * Add new filter to the FilterGroup.
     *
     * @param  Filter  $new
     *
     * @return self
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
        $this->addFilter(Filter::createEqual($field, $value));
        return $this;
    }

    public function addFilterNotEqual(string $field, mixed $value): self
    {
        $this->addFilter(Filter::createNotEqual($field, $value));
        return $this;
    }

    public function addFilterGreaterThan(string $field, mixed $value): self
    {
        $this->addFilter(Filter::createGreaterThan($field, $value));
        return $this;
    }

    public function addFilterGreaterOrEqualThan(string $field, mixed $value): self
    {
        $this->addFilter(Filter::createGreaterOrEqualThan($field, $value));
        return $this;
    }

    public function addFilterLessThan(string $field, mixed $value): self
    {
        $this->addFilter(Filter::createLessThan($field, $value));
        return $this;
    }

    public function addFilterLessOrEqualThan(string $field, mixed $value): self
    {
        $this->addFilter(Filter::createLessOrEqualThan($field, $value));
        return $this;
    }

    /**
     * @param  string  $field
     * @param  array<scalar>  $value
     * @return $this
     */
    public function addFilterIn(string $field, array $value): self
    {
        $this->addFilter(Filter::createIn($field, $value));
        return $this;
    }

    /**
     * @param  string  $field
     * @param  array<scalar>  $value
     * @return $this
     */
    public function addFilterNotIn(string $field, array $value): self
    {
        $this->addFilter(Filter::createNotIn($field, $value));
        return $this;
    }

    public function addFilterLike(string $field, string $value): self
    {
        $this->addFilter(Filter::createLike($field, $value));
        return $this;
    }

    public function addFilterNotLike(string $field, string $value): self
    {
        $this->addFilter(Filter::createNotLike($field, $value));
        return $this;
    }

    public function addFilterContains(string $field, string $value): self
    {
        $this->addFilter(Filter::createContains($field, $value));
        return $this;
    }

    public function addFilterNotContains(string $field, string $value): self
    {
        $this->addFilter(Filter::createNotContains($field, $value));
        return $this;
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
