<?php

declare(strict_types=1);

namespace ComplexHeart\Domain\Criteria;

use ComplexHeart\Domain\Model\ValueObjects\EnumValue;

/**
 * Class Operator
 *
 * @author Unay Santisteban <usantisteban@othercode.es>
 * @package ComplexHeart\Domain\Criteria
 */
final class Operator extends EnumValue
{
    public const EQUAL = '=';
    public const NOT_EQUAL = '!=';
    public const GT = '>';
    public const GTE = '>=';
    public const LT = '<';
    public const LTE = '<=';
    public const IN = 'in';
    public const NOT_IN = 'notIn';
    public const LIKE = 'like';

    public static function equal(): self
    {
        return new self(self::EQUAL);
    }

    public static function notEqual(): self
    {
        return new self(self::NOT_EQUAL);
    }

    public static function gt(): self
    {
        return new self(self::GT);
    }

    public static function gte(): self
    {
        return new self(self::GTE);
    }

    public static function lt(): self
    {
        return new self(self::LT);
    }

    public static function lte(): self
    {
        return new self(self::LTE);
    }

    public static function in(): self
    {
        return new self(self::IN);
    }

    public static function notIn(): self
    {
        return new self(self::NOT_IN);
    }

    public static function like(): self
    {
        return new self(self::LIKE);
    }
}