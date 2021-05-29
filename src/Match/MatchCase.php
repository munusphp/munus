<?php

declare(strict_types=1);

namespace Munus\Match;

/**
 * @template T
 * @template U
 */
interface MatchCase
{
    /**
     * @param T $value
     */
    public function match($value): bool;

    /**
     * @param T $value
     *
     * @return U
     */
    public function apply($value);
}
