<?php

declare(strict_types=1);

namespace Munus;

use Munus\Collection\Iterator;
use Munus\Collection\Stream;
use Munus\Collection\Stream\Collector;
use Munus\Control\Option;
use Munus\Control\TryTo;
use Munus\Value\Comparator;

/**
 * @template T
 */
abstract class Value
{
    abstract public function isEmpty(): bool;

    abstract public function isSingleValued(): bool;

    /**
     * @return T
     */
    abstract public function get();

    /**
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
     * @param T $other
     *
     * @return T
     */
    public function getOrElse($other)
    {
        return $this->isEmpty() ? $other : $this->get();
    }

    /**
     * @return T|null
     */
    public function getOrNull()
    {
        return $this->isEmpty() ? null : $this->get();
    }

    /**
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
     * @param callable():T $supplier
     *
     * @return T
     */
    public function getOrElseTry(callable $supplier)
    {
        return $this->isEmpty() ? TryTo::run($supplier)->get() : $this->get();
    }

    /**
     * @param T $object
     */
    public function equals($object): bool
    {
        return Comparator::equals($this->get(), $object);
    }

    /**
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

        return Stream::cons($iterator->next(), function () use ($iterator) {
            return $iterator->hasNext() ? $iterator->next() : Stream::empty();
        });
    }
}
