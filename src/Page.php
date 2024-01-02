<?php

declare(strict_types=1);

namespace ComplexHeart\Domain\Criteria;

use ComplexHeart\Domain\Contracts\Model\ValueObject;
use ComplexHeart\Domain\Model\IsValueObject;

/**
 * Class Page
 *
 * @author Unay Santisteban <usantisteban@othercode.io>
 * @package ComplexHeart\SDK\Domain\Criteria
 */
final class Page implements ValueObject
{
    use IsValueObject;

    /**
     * Page constructor.
     *
     * @param  int  $limit
     * @param  int  $offset
     */
    public function __construct(
        private readonly int $limit = 25,
        private readonly int $offset = 0,
    ) {
        $this->check();
    }

    public static function create(int $limit = 25, int $offset = 0): self
    {
        return new self($limit, $offset);
    }

    protected function invariantLimitValueMustBePositive(): bool
    {
        return $this->limit >= 0;
    }

    protected function invariantOffsetValueMustBePositive(): bool
    {
        return $this->offset >= 0;
    }

    public function limit(): int
    {
        return $this->limit;
    }

    public function offset(): int
    {
        return $this->offset;
    }

    public function __toString(): string
    {
        return sprintf('%s.%s', $this->limit, $this->offset);
    }
}
