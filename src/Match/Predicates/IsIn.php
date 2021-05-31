<?php

declare(strict_types=1);

namespace Munus\Match\Predicates;

use Munus\Match\Predicate;

/**
 * @template T
 */
class IsIn implements Predicate
{
    /**
     * @var iterable<T>
     */
    private $values;

    public function __construct(iterable $values)
    {
        $this->values = $values;
    }

    /**
     * @param T $value
     */
    public function meet($value): bool
    {
        return in_array($value, (array) $this->values, true);
    }
}
