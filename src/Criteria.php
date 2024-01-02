<?php

declare(strict_types=1);

namespace ComplexHeart\Domain\Criteria;

use ComplexHeart\Domain\Contracts\Model\ValueObject;
use ComplexHeart\Domain\Model\IsValueObject;

/**
 * Class Criteria
 *
 * @author Unay Santisteban <usantisteban@othercode.io>
 * @package ComplexHeart\SDK\Domain\Criteria
 */
final class Criteria implements ValueObject
{
    use IsValueObject;

    /**
     * Criteria constructor.
     *
     * @param  FilterGroup<Filter>  $filters
     * @param  Order  $order
     * @param  Page  $page
     */
    public function __construct(
        private readonly FilterGroup $filters,
        private readonly Order $order,
        private readonly Page $page,
    ) {
        $this->check();
    }

    public static function create(FilterGroup $filters, Order $order, Page $page): self
    {
        return new self($filters, $order, $page);
    }

    public static function createDefault(): self
    {
        return self::create(FilterGroup::create(), Order::none(), Page::create());
    }

    public function withFilters(FilterGroup $filters): self
    {
        return new self($filters, $this->order, $this->page);
    }

    public function withOrder(Order $order): self
    {
        return new self($this->filters, $order, $this->page);
    }

    public function withOrderBy(string $field): self
    {
        return new self($this->filters, Order::create($field, $this->orderType()), $this->page);
    }

    public function withOrderType(string $type): self
    {
        return new self($this->filters, Order::create($this->orderBy(), $type), $this->page);
    }

    public function withPage(Page $page): self
    {
        return new self($this->filters, $this->order, $page);
    }

    public function withPageOffset(int $offset): self
    {
        return new self($this->filters, $this->order, Page::create($this->pageLimit(), $offset));
    }

    public function withPageLimit(int $limit): self
    {
        return new self($this->filters, $this->order, Page::create($limit, $this->pageOffset()));
    }

    public function filters(): FilterGroup
    {
        return $this->filters;
    }

    public function addFilterEqual(string $field, mixed $value): self
    {
        $this->filters->addFilter(Filter::createEqual($field, $value));
        return $this;
    }

    public function addFilterNotEqual(string $field, mixed $value): self
    {
        $this->filters->addFilter(Filter::createNotEqual($field, $value));
        return $this;
    }

    public function addFilterGreaterThan(string $field, string $value): self
    {
        $this->filters->addFilter(Filter::createGreaterThan($field, $value));
        return $this;
    }

    public function addFilterGreaterOrEqualThan(string $field, string $value): self
    {
        $this->filters->addFilter(Filter::createGreaterOrEqualThan($field, $value));
        return $this;
    }

    public function addFilterLessThan(string $field, string $value): self
    {
        $this->filters->addFilter(Filter::createLessThan($field, $value));
        return $this;
    }

    public function addFilterLessOrEqualThan(string $field, string $value): self
    {
        $this->filters->addFilter(Filter::createLessOrEqualThan($field, $value));
        return $this;
    }

    /**
     * @param  string  $field
     * @param  array<scalar>  $value
     * @return $this
     */
    public function addFilterIn(string $field, array $value): self
    {
        $this->filters->addFilter(Filter::createIn($field, $value));
        return $this;
    }

    /**
     * @param  string  $field
     * @param  array<scalar>  $value
     * @return $this
     */
    public function addFilterNotIn(string $field, array $value): self
    {
        $this->filters->addFilter(Filter::createNotIn($field, $value));
        return $this;
    }

    public function addFilterLike(string $field, string $value): self
    {
        $this->filters->addFilter(Filter::createLike($field, $value));
        return $this;
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
        return $this->order->type();
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
        return sprintf('%s#%s#%s', $this->filters, $this->order, $this->page);
    }
}
