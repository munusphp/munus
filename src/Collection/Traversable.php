<?php

declare(strict_types=1);

namespace Munus\Collection;

use Munus\Control\Option;
use Munus\Exception\UnsupportedOperationException;
use Munus\Value;
use Munus\Value\Comparator;

/**
 * An abstraction for inherently recursive, multi-valued data structures. The order of elements is determined by
 * Iterator, which may vary each time it is called.
 *
 * @template T
 *
 * @template-extends Value<T>
 *
 * @implements \IteratorAggregate<int, T>
 */
abstract class Traversable extends Value implements \IteratorAggregate
{
    /**
     * Computes the number of elements of this traversable.
     */
    abstract public function length(): int;

    /**
     * @throws \RuntimeException if is empty
     *
     * @return T
     */
    abstract public function head();

    /**
     * @throws \RuntimeException if is empty
     *
     * @return Traversable<T>
     */
    abstract public function tail();

    /**
     * @template U
     *
     * @param callable(T): U $mapper
     *
     * @return Traversable<U>
     */
    abstract public function map(callable $mapper);

    /**
     * @template U
     *
     * @param callable(T): Traversable<U> $mapper
     *
     * @return Value<U>
     */
    abstract public function flatMap(callable $mapper);

    /**
     * Returns a new Traversable consisting of all elements which satisfy the given predicate.
     *
     * @param callable(T):bool $predicate
     *
     * @return Traversable<T>
     */
    abstract public function filter(callable $predicate);

    /**
     * @return Traversable<T>
     */
    abstract public function sorted();

    /**
     * @return Traversable<T>
     */
    abstract public function take(int $n);

    /**
     * @return Traversable<T>
     */
    abstract public function drop(int $n);

    /**
     * Drops elements while the predicate holds for the current element.
     *
     * @param callable(T):bool $predicate
     *
     * @return Traversable<T>
     */
    abstract public function dropWhile(callable $predicate);

    /**
     * Drops elements until the predicate holds for the current element.
     *
     * @param callable(T):bool $predicate
     *
     * @return Traversable<T>
     */
    public function dropUntil(callable $predicate)
    {
        return $this->dropWhile(/** @param T $value */ function ($value) use ($predicate): bool {
            return !$predicate($value);
        });
    }

    /**
     * Returns a new Traversable consisting of all elements which do not satisfy the given predicate.
     *
     * @param callable(T):bool $predicate
     *
     * @return Traversable<T>
     */
    public function filterNot(callable $predicate)
    {
        return $this->filter(/** @param T $value */ function ($value) use ($predicate): bool {
            return !$predicate($value);
        });
    }

    /**
     * Returns true if any element of this collection match the provided predicate.
     * May not evaluate the predicate on all elements if not necessary for determining the result.
     *
     * @param callable(T):bool $predicate
     */
    public function anyMatch(callable $predicate): bool
    {
        $iterator = $this->getIterator();
        while ($iterator->hasNext()) {
            if ($predicate($iterator->next())) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true if all elements of this collection match the provided predicate.
     * May not evaluate the predicate on all elements if not necessary for determining the result.
     * If collection is empty true is returned.
     *
     * @param callable(T):bool $predicate
     */
    public function allMatch(callable $predicate): bool
    {
        $iterator = $this->getIterator();
        while ($iterator->hasNext()) {
            if (!$predicate($iterator->next())) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns true if no elements of this collection match the provided predicate.
     * May not evaluate the predicate on all elements if not necessary for determining the result.
     * If collection is empty true is returned.
     *
     * @param callable(T):bool $predicate
     */
    public function noneMatch(callable $predicate): bool
    {
        $iterator = $this->getIterator();
        while ($iterator->hasNext()) {
            if ($predicate($iterator->next())) {
                return false;
            }
        }

        return true;
    }

    /**
     * Returns true if the two specified collections have no elements in common.
     *
     * @param Traversable<T> $traversable
     */
    public function disjoint(self $traversable): bool
    {
        if ($this->isEmpty() || $traversable->isEmpty()) {
            return true;
        }

        $iterator = $traversable->getIterator();
        while ($iterator->hasNext()) {
            if ($this->contains($iterator->next())) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return T
     */
    public function get()
    {
        return $this->head();
    }

    public function isSingleValued(): bool
    {
        return false;
    }

    /**
     * @return Iterator<T>
     */
    public function iterator(): Iterator
    {
        return new Iterator($this);
    }

    /**
     * @param callable(T): bool $predicate
     *
     * @return Option<T>
     */
    public function find(callable $predicate): Option
    {
        $iterator = $this->iterator();
        while ($iterator->hasNext()) {
            $next = $iterator->next();
            if ($predicate($next) === true) {
                return Option::some($next);
            }
        }

        return Option::none();
    }

    /**
     * @return Option<T>
     */
    public function findFirst(): Option
    {
        return $this->isEmpty() ? Option::none() : Option::some($this->head());
    }

    public function equals(mixed $object): bool
    {
        if ($object === $this) {
            return true;
        }

        if (!is_object($object) || !$object instanceof Traversable) {
            return false;
        }

        $iterator1 = $object->iterator();
        $iterator2 = $this->iterator();

        while ($iterator1->hasNext() && $iterator2->hasNext()) {
            if (!Comparator::equals($iterator1->next(), $iterator2->next())) {
                return false;
            }
        }

        return $iterator1->hasNext() === $iterator2->hasNext();
    }

    /**
     * @param callable(T,T):T $operation
     *
     * @return T
     */
    public function reduce(callable $operation)
    {
        return $this->iterator()->reduce($operation);
    }

    /**
     * @template U
     *
     * @param U               $zero
     * @param callable(U,T):U $combine
     *
     * @return U
     */
    public function fold($zero, callable $combine)
    {
        return $this->iterator()->fold($zero, $combine);
    }

    public function sum(): float|int
    {
        return $this->fold(0,
            /**
             * @param T $x
             */
            function (float|int $sum, $x): float|int {
                if (!is_numeric($x)) {
                    throw new UnsupportedOperationException('not numeric value');
                }

                return $sum + ($x * 1);
            }
        );
    }

    public function product(): float|int
    {
        return $this->fold(1,
            /**
             * @param T $x
             */
            function (float|int $product, $x): float|int {
                if (!is_numeric($x)) {
                    throw new UnsupportedOperationException('not numeric value');
                }

                return $product * ($x * 1);
            }
        );
    }

    /**
     * @throws UnsupportedOperationException
     */
    public function average(): float
    {
        if ($this->isEmpty()) {
            throw new UnsupportedOperationException('division by zero not possible');
        }

        return (float) ($this->sum() / $this->length());
    }

    /**
     * @return Option<T>
     */
    public function min(): Option
    {
        if ($this->isEmpty()) {
            return Option::none();
        }

        return Option::of($this->fold($this->head(), min(...)));
    }

    /**
     * @return Option<T>
     */
    public function max(): Option
    {
        if ($this->isEmpty()) {
            return Option::none();
        }

        return Option::of($this->fold($this->head(), max(...)));
    }

    /**
     * Counts the elements which satisfy the given predicate.
     *
     * @param callable(T): bool $predicate
     */
    public function count(callable $predicate): int
    {
        return $this->fold(0, /** @param T $value */ function (int $count, $value) use ($predicate): int {
            return $predicate($value) === true ? ++$count : $count;
        });
    }

    /**
     * @return Iterator<T>
     */
    public function getIterator(): \Traversable
    {
        return $this->iterator();
    }
}
