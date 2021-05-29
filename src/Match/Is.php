<?php

declare(strict_types=1);

namespace Munus\Match;

use Munus\Collection\GenericList;

/**
 * @template T
 */
class Is
{
    /**
     * @var callable(T):bool
     */
    private $callable;

    /**
     * @param callable(T):bool $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @psalm-template U
     *
     * @param U $matchValue
     *
     * @return Is<U>
     */
    public static function value($matchValue): Is
    {
        $callable = /** @param T $value */ function ($value) use ($matchValue): bool {
            return $value === $matchValue;
        };

        return new Is($callable);
    }

    /**
     * @return Is<T>
     */
    public static function in(iterable $values): Is
    {
        $callable = /** @param T $value */ function ($value) use ($values): bool {
            return in_array($value, (array) $values, true);
        };

        return new Is($callable);
    }

    /**
     * @return Is<T>
     */
    public static function instance(string $className): Is
    {
        $callable = /** @param T $value */ function ($value) use ($className): bool {
            return $value instanceof $className;
        };

        return new Is($callable);
    }

    public static function null(): Is
    {
        $callable = /** @param T $value */ function ($value): bool {
            return is_null($value);
        };

        return new Is($callable);
    }

    public static function notNull(): Is
    {
        $callable = /** @param T $value */ function ($value): bool {
            return !is_null($value);
        };

        return new Is($callable);
    }

    public static function anyOf(Is ...$predicates): Is
    {
        $callable = /** @param T $value */ function ($value) use ($predicates): bool {
            return !GenericList::ofAll($predicates)
                ->filter(function (Is $predicate) use ($value): bool {
                    return $predicate->equals($value);
                })->isEmpty();
        };

        return new Is($callable);
    }

    public static function noneOf(Is ...$predicates): Is
    {
        $callable = /** @param T $value */ function ($value) use ($predicates): bool {
            return GenericList::ofAll($predicates)
                ->filter(function (Is $predicate) use ($value): bool {
                    return $predicate->equals($value);
                })->isEmpty();
        };

        return new Is($callable);
    }

    public static function allOf(Is ...$predicates): Is
    {
        $callable = /** @param T $value */ function ($value) use ($predicates): bool {
            $predicatesList = GenericList::ofAll($predicates);
            return $predicatesList->count(function (Is $predicate) use ($value): bool {
                return $predicate->equals($value);
            }) === $predicatesList->length();
        };

        return new Is($callable);
    }

    /**
     * @param T $value
     */
    public function equals($value): bool
    {
        return ($this->callable)($value);
    }
}
