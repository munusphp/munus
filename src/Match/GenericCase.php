<?php

declare(strict_types=1);

namespace Munus\Match;

use Munus\Match\GenericCase\GenericCaseCallable;
use Munus\Match\GenericCase\GenericCaseStatic;

/**
 * @template T
 */
abstract class GenericCase implements MatchCase
{
    /**
     * @var T|Predicate<T>
     */
    protected $value;

    /**
     * @psalm-template U
     *
     * @param T|Predicate<T> $value
     * @param callable(T):U  $callable
     */
    public static function of($value, callable $callable): GenericCase
    {
        return new GenericCaseCallable($value, $callable);
    }

    /**
     * @psalm-template U
     *
     * @param T|Predicate<T> $value
     * @param U              $other
     */
    public static function ofStatic($value, $other): GenericCase
    {
        return new GenericCaseStatic($value, $other);
    }

    /**
     * @param T $value
     */
    public function match($value): bool
    {
        return $this->value instanceof Predicate ? $this->value->meet($value) : $this->value === $value;
    }
}
