<?php

declare(strict_types=1);

namespace Munus\Match;

use Munus\Match\DefaultCase\DefaultCaseCallable;
use Munus\Match\DefaultCase\DefaultCaseStatic;

/**
 * @template T
 */
abstract class DefaultCase implements MatchCase
{
    /**
     * @psalm-template U
     *
     * @param callable(T):U $callable
     */
    public static function call(callable $callable): DefaultCase
    {
        return new DefaultCaseCallable($callable);
    }

    /**
     * @psalm-template U
     *
     * @param U $other
     *
     * @return DefaultCase<U>
     */
    public static function of($other): DefaultCase
    {
        return new DefaultCaseStatic($other);
    }

    /**
     * @param T $value
     */
    public function match($value): bool
    {
        return true;
    }
}
