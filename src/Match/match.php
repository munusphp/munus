<?php

declare(strict_types=1);

namespace Munus\Match;

use Munus\GenericMatch;

/**
 * @psalm-template T
 *
 * @param T $value
 *
 * @return GenericMatch<T>
 */
function matchValue($value): GenericMatch
{
    return GenericMatch::value($value);
}

/**
 * @psalm-template T
 * @psalm-template U
 *
 * @param T|Predicate<T> $value
 * @param U              $other
 */
function caseOf($value, $other): GenericCase
{
    return GenericCase::of($value, $other);
}

/**
 * @psalm-template T
 * @psalm-template U
 *
 * @param T|Predicate<T> $value
 * @param callable(T):U  $callable
 */
function caseCall($value, callable $callable): GenericCase
{
    return GenericCase::call($value, $callable);
}

/**
 * @psalm-template T
 *
 * @param T $other
 *
 * @return DefaultCase<T>
 */
function defaultOf($other): DefaultCase
{
    return DefaultCase::of($other);
}

/**
 * @psalm-template T
 * @psalm-template U
 *
 * @param callable(T):U $callable
 */
function defaultCall(callable $callable): DefaultCase
{
    return DefaultCase::call($callable);
}
