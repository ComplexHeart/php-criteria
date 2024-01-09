<?php

declare(strict_types=1);

namespace ComplexHeart\Domain\Criteria\Errors;

use Error;
use Throwable;

/**
 * Class CriteriaError
 *
 * @author Unay Santisteban <usantisteban@othercode.io>
 * @package ComplexHeart\Domain\Criteria\Errors
 */
class CriteriaError extends Error
{
    /**
     * List of invariant violations.
     *
     * @var array<string>
     */
    private array $violations;

    /**
     * @param  string  $message
     * @param  int  $code
     * @param  Throwable|null  $previous
     * @param  array<string>  $violations
     */
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null, array $violations = [])
    {
        parent::__construct($message, $code, $previous);

        $this->violations = $violations;
    }

    /**
     * @param  string  $message
     * @param  array<string>  $violations
     * @param  int  $code
     * @param  Throwable|null  $previous
     * @return self
     */
    public static function create(string $message, array $violations, int $code = 0, Throwable $previous = null): self
    {
        return new self($message, $code, $previous, $violations);
    }

    /**
     * Returns the list of invariant violations.
     *
     * @return array<string>
     */
    public function violations(): array
    {
        return $this->violations;
    }
}
