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

    const DEFAULT_LIMIT = 25;
    const DEFAULT_OFFSET = 0;

    /**
     * Page constructor.
     *
     * @param  int  $limit
     * @param  int  $offset
     */
    public function __construct(
        private readonly int $limit = self::DEFAULT_LIMIT,
        private readonly int $offset = self::DEFAULT_OFFSET,
    ) {
        $this->check();
    }

    public static function create(int $limit = self::DEFAULT_LIMIT, int $offset = self::DEFAULT_OFFSET): self
    {
        return new self($limit, $offset);
    }

    public static function number(int $number, int $size = self::DEFAULT_LIMIT): self
    {
        return self::create($size, ($size * $number) - $size);
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
