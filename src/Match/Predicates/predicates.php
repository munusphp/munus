<?php

declare(strict_types=1);

namespace Munus\Match\Predicates;

use Munus\Match\Predicate;

/**
 * @psalm-template T
 *
 * @param Predicate<T> ...$predicates
 *
 * @return Predicate<T>
 */
function isAllOf(Predicate ...$predicates): Predicate
{
    return new IsAllOf(...$predicates);
}

/**
 * @psalm-template T
 *
 * @param Predicate<T> ...$predicates
 *
 * @return Predicate<T>
 */
function isAnyOf(Predicate ...$predicates): Predicate
{
    return new IsAnyOf(...$predicates);
}

/**
 * @psalm-template T
 *
 * @param iterable<T> $values
 *
 * @return Predicate<T>
 */
function isIn(iterable $values): Predicate
{
    return new IsIn($values);
}

/**
 * @return Predicate<object>
 */
function isInstanceOf(string $className): Predicate
{
    return new IsInstanceOf($className);
}

/**
 * @psalm-template T
 *
 * @param Predicate<T> ...$predicates
 *
 * @return Predicate<T>
 */
function isNoneOf(Predicate ...$predicates): Predicate
{
    return new IsNoneOf(...$predicates);
}

/**
 * @return Predicate<mixed>
 */
function isNotNull(): Predicate
{
    return new IsNotNull();
}

/**
 * @return Predicate<mixed>
 */
function isNull(): Predicate
{
    return new IsNull();
}

/**
 * @psalm-template T
 *
 * @param T $value
 *
 * @return Predicate<T>
 */
function isValue($value): Predicate
{
    return new IsValue($value);
}
