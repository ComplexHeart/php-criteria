<?php

declare(strict_types=1);

namespace ComplexHeart\Domain\Criteria\Contracts;

/**
 * Interface CriteriaSource
 *
 * @author Unay Santisteban <usantisteban@othercode.io>
 * @package ComplexHeart\Domain\Criteria\Contracts
 */
interface CriteriaSource
{
    /**
     * Provides the list of filter groups. Each filter group is a list of
     * filters. A filter is the combination of field or attribute, operator
     * and value:
     *
     * [
     *      [
     *          ["field" => "title", "operator" => "like", "value" => "to hero"],
     *          ["field" => "tag", "operator" => "in", "value" => ["beginner", "intermediate"]],
     *      ],
     * ];
     *
     * @return array<array<array<string, mixed>>>
     */
    public function filterGroups(): array;

    /**
     * One of: asc, desc, none or random.
     *
     * @return string
     */
    public function orderType(): string;

    /**
     * The field or attribute to order by.
     *
     * @return string
     */
    public function orderBy(): string;

    /**
     * Provides the size of a page.
     *
     * @return int
     */
    public function pageLimit(): int;

    /**
     * Provides the offset, by default should be 0. This value will be discarded
     * if page number returns a value > 0.
     *
     * @return int
     */
    public function pageOffset(): int;

    /**
     * Provides the page number. If value > 0, will be used to compute the offset.
     * @return int
     */
    public function pageNumber(): int;
}
