<?php

declare(strict_types=1);

namespace Munus;

use Munus\Collection\Iterator;
use Munus\Collection\Stream;
use Munus\Collection\Stream\Emptƴ;
use Munus\Collection\Traversable;
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
     * @param callable<T>
     */
    public function forEach(callable $consumer): void
    {
        $iterator = $this->iterator();
        while ($iterator->hasNext()) {
            $consumer($iterator->next());
        }
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
     * @param callable:T $supplier
     *
     * @return T
     */
    public function getOrElseTry(callable $supplier)
    {
        return $this->isEmpty() ? TryTo::run($supplier)->get() : $this->get();
    }

    public function equals($object): bool
    {
        return Comparator::equals($this->get(), $object);
    }

    public function toStream(): Stream
    {
        if ($this->isEmpty()) {
            return Emptƴ::instance();
        }

        if ($this->isSingleValued()) {
            return Stream::of($this->get());
        }

        if ($this instanceof Traversable) {
            return Stream::ofAll($this->iterator()->toArray());
        }
    }
}
