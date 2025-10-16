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

    public const DEFAULT_LIMIT = 25;
    public const DEFAULT_OFFSET = 0;

    /**
     * Page constructor.
     *
     * @param  int  $limit
     * @param  int  $offset
     */
    public function __construct(
        private readonly int $limit,
        private readonly int $offset,
    ) {
        $this->check();
    }

    protected function invariantLimitValueMustBePositive(): bool
    {
        return $this->limit >= 0;
    }

    protected function invariantOffsetValueMustBePositive(): bool
    {
        return $this->offset >= 0;
    }

    public static function create(int $limit, int $offset): self
    {
        return new self($limit, $offset);
    }

    public static function default(): self
    {
        return self::create(self::DEFAULT_LIMIT, self::DEFAULT_OFFSET);
    }

    public static function number(int $number, int $size = self::DEFAULT_LIMIT): self
    {
        return self::create($size, ($size * $number) - $size);
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
        return sprintf('%s %s', $this->limit, $this->offset);
    }
}
