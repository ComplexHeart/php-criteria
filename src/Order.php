<?php

declare(strict_types=1);

namespace ComplexHeart\Domain\Criteria;

use ComplexHeart\Domain\Contracts\Model\ValueObject;
use ComplexHeart\Domain\Model\IsValueObject;

/**
 * Class Order
 *
 * @author Unay Santisteban <usantisteban@othercode.io>
 * @package ComplexHeart\SDK\Domain\Criteria
 */
final class Order implements ValueObject
{
    use IsValueObject;

    /**
     * Order constructor.
     *
     * @param  string  $by
     * @param  OrderType  $type
     */
    public function __construct(
        private readonly string $by,
        private readonly OrderType $type,
    ) {
        $this->check();
    }

    protected function invariantOrderByValueMustContainOnlyAlphanumericalCharacters(): bool
    {
        return preg_match('/\w*/', $this->by) === 1;
    }

    public static function create(string $by, OrderType $type = OrderType::ASC): self
    {
        return new self($by, $type);
    }

    public static function createAscBy(string $by): self
    {
        return self::create($by);
    }

    public static function createDescBy(string $by): self
    {
        return self::create($by, OrderType::DESC);
    }

    public static function none(): self
    {
        return self::create('', OrderType::NONE);
    }

    public static function random(): self
    {
        return self::create('', OrderType::RANDOM);
    }

    public function by(): string
    {
        return $this->by;
    }

    public function type(): OrderType
    {
        return $this->type;
    }

    public function isNone(): bool
    {
        return $this->type === OrderType::NONE;
    }

    public function isRandom(): bool
    {
        return $this->type === OrderType::RANDOM;
    }

    public function __toString(): string
    {
        return sprintf('%s.%s', $this->by(), $this->type()->value);
    }
}
