<?php

declare(strict_types=1);

namespace ComplexHeart\Domain\Criteria\Contracts;

/**
 * Interface PaginatedResult
 *
 * @author Unay Santisteban <usantisteban@othercode.io>
 */
interface PaginatedResult
{
    /**
     * Get the items for the current page
     *
     * @return array<int, mixed>
     */
    public function items(): array;

    /**
     * Get total number of items across all pages
     *
     * @return int
     */
    public function total(): int;

    /**
     * Get items per page
     *
     * @return int
     */
    public function perPage(): int;

    /**
     * Get current page number (1-indexed)
     *
     * @return int
     */
    public function currentPage(): int;

    /**
     * Get last page number
     *
     * @return int
     */
    public function lastPage(): int;

    /**
     * Check if there are more pages after the current one
     *
     * @return bool
     */
    public function hasMorePages(): bool;

    /**
     * Get the count of items on current page
     *
     * @return int
     */
    public function count(): int;

    /**
     * Check if the current page is empty
     *
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * Check if the current page is not empty
     *
     * @return bool
     */
    public function isNotEmpty(): bool;
}
