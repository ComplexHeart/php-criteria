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
    case ASC = 'ASC';
    case DESC = 'DESC';
    case NONE = 'NONE';
    case RANDOM = 'RANDOM';

    public static function make(string $value): self
    {
        return self::from(strtoupper($value));
    }
}
