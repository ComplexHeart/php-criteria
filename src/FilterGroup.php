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
 */
final class FilterGroup extends TypedCollection
{
    protected string $keyType = 'integer';

    protected string $valueType = Filter::class;

    /**
     * FilterGroup constructor.
     *
     * @param  array<Filter>  $items
     */
    public function __construct(array $items = [])
    {
        parent::__construct(array_unique($items));
    }

    public static function create(Filter ...$filters): self
    {
        return new self($filters);
    }

    /**
     * @param  array<string, scalar>|array<string>  $filters
     * @return self
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
    public function addFilter(Filter $new): FilterGroup
    {
        if ($this->filter(fn(Filter $filter): bool => $filter->equals($new))->count() > 0) {
            return $this;
        }

        $this->items[] = $new;

        return $this;
    }

    /**
     * Transform the FilterGroup to a string.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->toBase()
            ->map(fn(Filter $filter): string => $filter->__toString())
            ->join('+');
    }
}
