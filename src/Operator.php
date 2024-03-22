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
    case NOT_IN = 'not in';
    case LIKE = 'like';
    case NOT_LIKE = 'not like';
    case CONTAINS = 'contains';
    case NOT_CONTAINS = 'not contains';

    public static function create(string $value): self
    {
        return match ($value) {
            'eq' => self::EQUAL,
            'neq', 'ne' => self::NOT_EQUAL,
            'gt' => self::GT,
            'gte', 'ge' => self::GTE,
            'lt' => self::LT,
            'lte', 'le' => self::LTE,
            'nin', 'out' => self::NOT_IN,
            'nlike' => self::NOT_LIKE,
            'ncontains' => self::NOT_CONTAINS,
            default => self::from($value)
        };
    }
}
