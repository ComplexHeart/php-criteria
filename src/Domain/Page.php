<?php

declare(strict_types=1);

namespace ComplexHeart\Domain\Criteria;

use ComplexHeart\Contracts\Domain\Model\ValueObject;
use ComplexHeart\Domain\Model\Traits\IsValueObject;


/**
 * Class Page
 *
 * @author Unay Santisteban <usantisteban@othercode.es>
 * @package ComplexHeart\SDK\Domain\Criteria
 */
final class Page implements ValueObject
{
    use IsValueObject;

    private int $limit;

    private int $offset;

    /**
     * Page constructor.
     *
     * @param  int  $limit
     * @param  int  $offset
     */
    public function __construct(int $limit = 25, int $offset = 0)
    {
        $this->initialize(compact('limit', 'offset'));
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