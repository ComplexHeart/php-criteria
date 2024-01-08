<?php

declare(strict_types=1);

namespace ComplexHeart\Domain\Criteria;

/**
 * Class Operator
 *
 * @author Unay Santisteban <usantisteban@othercode.io>
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
    case NOT_LIKE = 'notLike';
    case CONTAINS = 'contains';
    case NOT_CONTAINS = 'notContains';

    public static function make(string $value): self
    {
        return self::from($value);
    }
}
