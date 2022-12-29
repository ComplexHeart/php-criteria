<?php

declare(strict_types=1);

namespace ComplexHeart\Domain\Criteria;


/**
 * Class Operator
 *
 * @author Unay Santisteban <usantisteban@othercode.es>
 * @package ComplexHeart\Domain\Criteria
 */
enum Operator: string
{
    case EQUAL = '=';
    case NOT_EQUAL = '!=';
    case GT = '>';
    case GTE = '>=';
    case LT = '<';
    case LTE = '<=';
    case IN = 'in';
    case NOT_IN = 'notIn';
    case LIKE = 'like';

    public static function make(string $value): self
    {
        return self::from($value);
    }

    public static function equal(): self
    {
        return self::EQUAL;
    }

    public static function notEqual(): self
    {
        return self::NOT_EQUAL;
    }

    public static function gt(): self
    {
        return self::GT;
    }

    public static function gte(): self
    {
        return self::GTE;
    }

    public static function lt(): self
    {
        return self::LT;
    }

    public static function lte(): self
    {
        return self::LTE;
    }

    public static function in(): self
    {
        return self::IN;
    }

    public static function notIn(): self
    {
        return self::NOT_IN;
    }

    public static function like(): self
    {
        return self::LIKE;
    }
}
