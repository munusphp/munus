<?php

declare(strict_types=1);

namespace Munus\Match\Predicates;

use Munus\Match\Predicate;

/**
 * @template T
 */
class IsValue implements Predicate
{
    /**
     * @var T
     */
    private $value;

    /**
     * @param T $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param T $value
     */
    public function meet($value): bool
    {
        return $this->value === $value;
    }
}
