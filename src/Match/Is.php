<?php

declare(strict_types=1);

namespace Munus\Match;

use Munus\Match\Predicates\IsAllOf;
use Munus\Match\Predicates\IsAnyOf;
use Munus\Match\Predicates\IsIn;
use Munus\Match\Predicates\IsInstance;
use Munus\Match\Predicates\IsNoneOf;
use Munus\Match\Predicates\IsNotNull;
use Munus\Match\Predicates\IsNull;
use Munus\Match\Predicates\IsValue;

final class Is
{
    private function __construct()
    {
    }

    /**
     * @psalm-template T
     *
     * @param T $value
     *
     * @return Predicate<T>
     */
    public static function value($value): Predicate
    {
        return new IsValue($value);
    }

    /**
     * @psalm-template T
     *
     * @param iterable<T> $values
     *
     * @return Predicate<T>
     */
    public static function in(iterable $values): Predicate
    {
        return new IsIn($values);
    }

    /**
     * @return Predicate<object>
     */
    public static function instance(string $className): Predicate
    {
        return new IsInstance($className);
    }

    /**
     * @return Predicate<mixed>
     */
    public static function null(): Predicate
    {
        return new IsNull();
    }

    /**
     * @return Predicate<mixed>
     */
    public static function notNull(): Predicate
    {
        return new IsNotNull();
    }

    /**
     * @psalm-template T
     *
     * @param Predicate<T> ...$predicates
     *
     * @return Predicate<T>
     */
    public static function anyOf(Predicate ...$predicates): Predicate
    {
        return new IsAnyOf(...$predicates);
    }

    /**
     * @psalm-template T
     *
     * @param Predicate<T> ...$predicates
     *
     * @return Predicate<T>
     */
    public static function noneOf(Predicate ...$predicates): Predicate
    {
        return new IsNoneOf(...$predicates);
    }

    /**
     * @psalm-template T
     *
     * @param Predicate<T> ...$predicates
     *
     * @return Predicate<T>
     */
    public static function allOf(Predicate ...$predicates): Predicate
    {
        return new IsAllOf(...$predicates);
    }
}
