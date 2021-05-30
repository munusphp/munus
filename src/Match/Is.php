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

/**
 * @template T
 */
abstract class Is
{
    /**
     * @psalm-template U
     *
     * @param U $value
     *
     * @return Is<U>
     */
    public static function value($value): Is
    {
        return new IsValue($value);
    }

    /**
     * @return Is<T>
     */
    public static function in(iterable $values): Is
    {
        return new IsIn($values);
    }

    /**
     * @return Is<T>
     */
    public static function instance(string $className): Is
    {
        return new IsInstance($className);
    }

    /**
     * @return Is<T>
     */
    public static function null(): Is
    {
        return new IsNull();
    }

    /**
     * @return Is<T>
     */
    public static function notNull(): Is
    {
        return new IsNotNull();
    }

    /**
     * @return Is<T>
     */
    public static function anyOf(Is ...$predicates): Is
    {
        return new IsAnyOf(...$predicates);
    }

    /**
     * @return Is<T>
     */
    public static function noneOf(Is ...$predicates): Is
    {
        return new IsNoneOf(...$predicates);
    }

    /**
     * @return Is<T>
     */
    public static function allOf(Is ...$predicates): Is
    {
        return new IsAllOf(...$predicates);
    }

    /**
     * @param T $value
     */
    abstract public function meet($value): bool;
}
