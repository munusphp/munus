<?php

declare(strict_types=1);

namespace Munus\Match;

/**
 * @template T
 */
interface Predicate
{
    /**
     * @param T $value
     */
    public function meet($value): bool;
}
