<?php

declare(strict_types=1);

namespace ComplexHeart\Domain\Criteria;

use Closure;
use ComplexHeart\Domain\Contracts\Model\ValueObject;
use ComplexHeart\Domain\Criteria\Contracts\CriteriaSource;
use ComplexHeart\Domain\Criteria\Errors\CriteriaError;
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
        $this->check();
    }

    protected function invariantGroupsMustBeArrayOfFilterGroup(): bool
    {
        foreach ($this->groups as $group) {
            if (!($group instanceof FilterGroup)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param  array<string>  $violations
     * @return void
     */
    protected function invariantHandler(array $violations): void
    {
        throw CriteriaError::create('Unable to create criteria object.', $violations);
    }

    /**
     * @param  array<FilterGroup<Filter>>  $groups
     * @param  Order  $order
     * @param  Page  $page
     * @return Criteria
     */
    public static function create(array $groups, Order $order, Page $page): self
    {
        return new self(groups: $groups, order: $order, page: $page);
    }

    public static function default(): self
    {
        return self::create(
            groups: [],
            order: Order::none(),
            page: Page::default()
        );
    }

    /**
     * Creates a new instance of Criteria from the given data source.
     *
     * @param  CriteriaSource  $source
     * @return Criteria
     */
    public static function fromSource(CriteriaSource $source): self
    {
        return self::create(
            groups: map(
                fn (array $g): FilterGroup => FilterGroup::fromArray($g),
                $source->filterGroups()
            ),
            order: Order::create($source->orderBy(), OrderType::make($source->orderType())),
            page: $source->pageNumber() > 0
                ? Page::number($source->pageNumber(), $source->pageLimit())
                : Page::create($source->pageLimit(), $source->pageOffset())
        );
    }

    /**
     * Returns a new instance of the criteria with the given FilterGroups.
     *
     * @param  array<FilterGroup<Filter>>  $groups
     * @return Criteria
     */
    public function withFilterGroups(array $groups): self
    {
        return self::create(
            groups: $groups,
            order: $this->order,
            page: $this->page
        );
    }

    /**
     * Returns a new instance of the criteria adding the given FilterGroup.
     *
     * @param  FilterGroup|Closure  $group
     * @return Criteria
     */
    public function withFilterGroup(FilterGroup|Closure $group): self
    {
        $group = $group instanceof FilterGroup ? $group : $group(FilterGroup::create());

        // push single FilterGroup into an array.
        $group = is_array($group) ? $group : [$group];

        return $this->withFilterGroups(groups: array_merge($this->groups, $group));
    }

    public function withOrder(Order $order): self
    {
        return self::create(groups: $this->groups, order: $order, page: $this->page);
    }

    public function withOrderRandom(): self
    {
        return self::create(groups: $this->groups, order: Order::random(), page: $this->page);
    }

    public function withOrderBy(string $field): self
    {
        return self::create(
            groups: $this->groups,
            order: Order::create($field, $this->order->type()),
            page: $this->page
        );
    }

    public function withOrderType(string $type): self
    {
        return self::create(
            groups: $this->groups,
            order: Order::create($this->orderBy(), OrderType::make($type)),
            page: $this->page
        );
    }

    public function withPage(Page $page): self
    {
        return self::create(groups: $this->groups, order: $this->order, page: $page);
    }

    public function withPageLimit(int $limit): self
    {
        return self::create(
            groups: $this->groups,
            order: $this->order,
            page: Page::create($limit, $this->pageOffset())
        );
    }

    public function withPageOffset(int $offset): self
    {
        return self::create(
            groups: $this->groups,
            order: $this->order,
            page: Page::create($this->pageLimit(), $offset)
        );
    }

    public function withPageNumber(int $number, ?int $size = null): self
    {
        return self::create(
            groups: $this->groups,
            order: $this->order,
            page: Page::number($number, is_null($size) ? $this->pageLimit() : $size)
        );
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

    public function pageLimit(): int
    {
        return $this->page->limit();
    }

    public function pageOffset(): int
    {
        return $this->page->offset();
    }

    public function __toString(): string
    {
        $groups = join('||', map(fn (FilterGroup $group): string => $group->__toString(), $this->groups));
        $order = $this->order->__toString();
        $page = $this->page->__toString();

        return sprintf('%s#%s#%s', $groups, $order, $page);
    }
}
