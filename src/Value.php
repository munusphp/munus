<?php

declare(strict_types=1);

namespace Munus;

use Munus\Collection\Iterator;
use Munus\Collection\Stream;
use Munus\Collection\Stream\Collector;
use Munus\Collection\Traversable;
use Munus\Control\Option;
use Munus\Control\TryTo;
use Munus\Value\Comparable;
use Munus\Value\Comparator;

/**
 * Value is the basic and most important type of this library. What you need to know about Value:
 *  - it is immutable by default
 *  - it is generic wrapper
 *  - it can be empty
 *  - it can be safely compared with other value.
 *
 * @template T
 */
abstract class Value implements Comparable
{
    /**
     * Checks, if the underlying value is absent.
     */
    abstract public function isEmpty(): bool;

    /**
     * States whether this is a single-valued type.
     */
    abstract public function isSingleValued(): bool;

    /**
     * Returns the underlying value or throw exception if there is no value (e.x. None).
     *
     * @return T
     */
    abstract public function get();

    /**
     * Maps the underlying value to a different type.
     *
     * @template U
     *
     * @param callable(T): U $mapper
     *
     * @return Value<U>
     */
    abstract public function map(callable $mapper);

    /**
     * @return Iterator<T>
     */
    abstract public function iterator(): Iterator;

    /**
     * Performs given action on first element.
     *
     * @param callable(T):void $action
     *
     * @return self<T>
     */
    public function peek(callable $action)
    {
        if (!$this->isEmpty()) {
            $action($this->get());
        }

        return $this;
    }

    /**
     * Run consumer on each element.
     *
     * @param callable(T):void $consumer
     */
    public function forEach(callable $consumer): void
    {
        $iterator = $this->iterator();
        while ($iterator->hasNext()) {
            $consumer($iterator->next());
        }
    }

    /**
     * Checks, if the given predicate is true for all elements.
     *
     * @param callable(T):bool $predicate
     */
    public function forAll(callable $predicate): bool
    {
        $iterator = $this->iterator();
        while ($iterator->hasNext()) {
            if ($predicate($iterator->next()) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check, if the given element is contained.
     *
     * @param T $element
     */
    public function contains($element): bool
    {
        $iterator = $this->iterator();
        while ($iterator->hasNext()) {
            if (Comparator::equals($iterator->next(), $element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param Traversable<T> $elements
     */
    public function containsAll(Traversable $elements): bool
    {
        $iterator = $elements->iterator();
        while ($iterator->hasNext()) {
            if (!$this->contains($iterator->next())) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param callable(T):bool $predicate
     */
    public function exists(callable $predicate): bool
    {
        $iterator = $this->iterator();
        while ($iterator->hasNext()) {
            if ($predicate($iterator->next()) === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns the underlying value if present, otherwise return $other.
     *
     * @param T $other
     *
     * @return T
     */
    public function getOrElse($other)
    {
        return $this->isEmpty() ? $other : $this->get();
    }

    /**
     * Returns the underlying value if present, otherwise returns null.
     *
     * @return T|null
     */
    public function getOrNull()
    {
        return $this->isEmpty() ? null : $this->get();
    }

    /**
     * Returns the underlying value if present, otherwise throws $throwable.
     *
     * @return T
     */
    public function getOrElseThrow(\Throwable $throwable)
    {
        if ($this->isEmpty()) {
            throw $throwable;
        }

        return $this->get();
    }

    /**
     * Returns the underlying value if present, otherwise returns the result of $supplier.
     *
     * @param callable():T $supplier
     *
     * @return T
     */
    public function getOrElseTry(callable $supplier)
    {
        return $this->isEmpty() ? TryTo::run($supplier)->get() : $this->get();
    }

    /**
     * Similar to "==" operator, but also checks congruence of structures and equality of contained values.
     */
    public function equals(mixed $other): bool
    {
        return Comparator::equals($this->get(), $other);
    }

    /**
     * Collects the underlying value(s) (if present) using the provided collector.
     *
     * @template R
     *
     * @param Collector<T,R> $collector
     *
     * @return R
     */
    public function collect(Collector $collector)
    {
        return $this->toStream()->collect($collector);
    }

    /**
     * @return Option<T>
     */
    public function toOption(): Option
    {
        if ($this instanceof Option) {
            return $this;
        }

        return $this->isEmpty() ? Option::none() : Option::some($this->get());
    }

    /**
     * @return Stream<T>
     */
    public function toStream(): Stream
    {
        if ($this->isEmpty()) {
            return Stream::empty();
        }

        if ($this->isSingleValued()) {
            return Stream::of($this->get());
        }

        $iterator = $this->iterator();

        /** @var Stream<T> $stream */
        $stream = Stream::cons($iterator->next(), function () use ($iterator) {
            return $iterator->hasNext() ? $iterator->next() : Stream::empty();
        });

        return $stream;
    }

    /**
     * @return array<T>
     */
    public function toArray(): array
    {
        if ($this->isEmpty()) {
            return [];
        }

        if ($this->isSingleValued()) {
            return [$this->get()];
        }

        return $this->iterator()->toArray();
    }
}
