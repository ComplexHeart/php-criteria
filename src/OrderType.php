<?php

declare(strict_types=1);

namespace ComplexHeart\Domain\Criteria;

/**
 * Class OrderType
 *
 * @author Unay Santisteban <usantisteban@othercode.io>
 * @package ComplexHeart\Domain\Criteria
 */
enum OrderType: string
{
    case ASC = 'asc';
    case DESC = 'desc';
    case NONE = 'none';
    case RANDOM = 'random';

    public static function make(string $value): self
    {
        return self::from($value);
    }
}
