<?php

declare(strict_types=1);

namespace ComplexHeart\Domain\Criteria;

use Closure;
use ComplexHeart\Domain\Contracts\Model\ValueObject;
use ComplexHeart\Domain\Model\IsValueObject;

use function Lambdish\Phunctional\map;

/**
 * Class Criteria
 *
 * @author Unay Santisteban <usantisteban@othercode.io>
 * @package ComplexHeart\Domain\Criteria
 */
final class Criteria implements ValueObject
{
    use IsValueObject;

    /**
     * Criteria constructor.
     *
     * @param  array<FilterGroup<Filter>>  $groups
     * @param  Order  $order
     * @param  Page  $page
     */
    public function __construct(
        private readonly array $groups,
        private readonly Order $order,
        private readonly Page $page,
    ) {
    }

    /**
     * @param  array<FilterGroup<Filter>>  $groups
     * @param  Order  $order
     * @param  Page  $page
     * @return Criteria
     */
    public static function create(array $groups, Order $order, Page $page): self
    {
        return new self($groups, $order, $page);
    }

    public static function default(): self
    {
        return self::create([], Order::none(), Page::create());
    }

    /**
     * Returns a new instance of the criteria with the given FilterGroups.
     *
     * @param  array<FilterGroup<Filter>>  $groups
     * @return Criteria
     */
    public function withFilterGroups(array $groups): self
    {
        return self::create($groups, $this->order, $this->page);
    }

    /**
     * Returns a new instance of the criteria adding the given FilterGroup.
     *
     * @param  FilterGroup|Closure  $group
     * @return $this
     */
    public function withFilterGroup(FilterGroup|Closure $group): self
    {
        if (is_callable($group)) {
            $group = $group(new FilterGroup());
        }

        return $this->withFilterGroups(array_merge($this->groups, [$group]));
    }

    public function withOrder(Order $order): self
    {
        return self::create($this->groups, $order, $this->page);
    }

    public function withOrderRandom(): self
    {
        return self::create($this->groups, Order::random(), $this->page);
    }

    public function withOrderBy(string $field): self
    {
        return self::create($this->groups, Order::create($field, $this->order->type()), $this->page);
    }

    public function withOrderType(string $type): self
    {
        return self::create(
            $this->groups,
            Order::create($this->orderBy(), OrderType::make($type)),
            $this->page
        );
    }

    public function withPage(Page $page): self
    {
        return self::create($this->groups, $this->order, $page);
    }

    public function withPageOffset(int $offset): self
    {
        return self::create(
            $this->groups,
            $this->order,
            Page::create($this->pageLimit(), $offset)
        );
    }

    public function withPageLimit(int $limit): self
    {
        return self::create($this->groups, $this->order, Page::create($limit, $this->pageOffset()));
    }

    /**
     * Returns the list of group filters.
     *
     * @return array<FilterGroup<Filter>>
     */
    public function groups(): array
    {
        return $this->groups;
    }

    public function order(): Order
    {
        return $this->order;
    }

    public function orderBy(): string
    {
        return $this->order->by();
    }

    public function orderType(): string
    {
        return $this->order->type()->value;
    }

    public function page(): Page
    {
        return $this->page;
    }

    public function pageOffset(): int
    {
        return $this->page->offset();
    }

    public function pageLimit(): int
    {
        return $this->page->limit();
    }

    public function __toString(): string
    {
        $groups = join('||', map(fn(FilterGroup $group): string => $group->__toString(), $this->groups));
        $order = $this->order->__toString();
        $page = $this->page->__toString();

        return sprintf('%s#%s#%s', $groups, $order, $page);
    }
}
