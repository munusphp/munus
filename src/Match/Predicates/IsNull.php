<?php

declare(strict_types=1);

namespace Munus\Match\Predicates;

use Munus\Match\Predicate;

/**
 * @template T
 */
class IsNull implements Predicate
{
    /**
     * @param T $value
     */
    public function meet($value): bool
    {
        return is_null($value);
    }
}
