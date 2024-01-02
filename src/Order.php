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

    public const TYPE_ASC = 'asc';
    public const TYPE_DESC = 'desc';
    public const TYPE_NONE = 'none';

    /**
     * Order constructor.
     *
     * @param  string  $by
     * @param  string  $type
     */
    public function __construct(
        private readonly string $by,
        private readonly string $type = self::TYPE_ASC,
    ) {
        $this->check();
    }

    protected function invariantOrderByValueMustContainOnlyAlphanumericalCharacters(): bool
    {
        return preg_match('/\w*/', $this->by) === 1;
    }

    protected function invariantOrderTypeValueMustBeOneOfAscDescOrNone(): bool
    {
        return in_array($this->type, [self::TYPE_ASC, self::TYPE_DESC, self::TYPE_NONE]);
    }

    public static function create(string $by, string $type = self::TYPE_ASC): self
    {
        return new self($by, $type);
    }

    public static function createAscBy(string $by): self
    {
        return self::create($by);
    }

    public static function createDescBy(string $by): self
    {
        return self::create($by, self::TYPE_DESC);
    }

    public static function none(): Order
    {
        return self::create('', self::TYPE_NONE);
    }

    public function by(): string
    {
        return $this->by;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function isNone(): bool
    {
        return $this->type === self::TYPE_NONE;
    }

    public function __toString(): string
    {
        return sprintf('%s.%s', $this->by(), $this->type());
    }
}
