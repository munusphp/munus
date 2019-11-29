<?php

declare(strict_types=1);

namespace Munus\Collection;

use Munus\Control\Option;
use Munus\Exception\UnsupportedOperationException;
use Munus\Value;
use Munus\Value\Comparator;

/**
 * @template T
 * @template-extends Value<T>
 */
abstract class Traversable extends Value
{
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
                return Option::of($next);
            }
        }

        return Option::of(null);
    }

    public function equals($object): bool
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

    public function reduce(callable $operation)
    {
        return $this->iterator()->reduce($operation);
    }

    /**
     * @template U
     *
     * @param U $zero
     *
     * @return U
     */
    public function fold($zero, callable $combine)
    {
        return $this->iterator()->fold($zero, $combine);
    }

    /**
     * @return int|float
     */
    public function sum()
    {
        return $this->fold(0, function ($sum, $x) {
            if (!is_numeric($x)) {
                throw new UnsupportedOperationException('not numeric value');
            }

            return $sum + ($x * 1);
        });
    }

    /**
     * @return int|float
     */
    public function product()
    {
        return $this->fold(1, function ($product, $x) {
            if (!is_numeric($x)) {
                throw new UnsupportedOperationException('not numeric value');
            }

            return $product * ($x * 1);
        });
    }

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

        return Option::of($this->fold($this->head(), function ($min, $x) {
            return $min <= $x ? $min : $x;
        }));
    }

    /**
     * @return Option<T>
     */
    public function max(): Option
    {
        if ($this->isEmpty()) {
            return Option::none();
        }

        return Option::of($this->fold($this->head(), function ($max, $x) {
            return $max >= $x ? $max : $x;
        }));
    }
}
