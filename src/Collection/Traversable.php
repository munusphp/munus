<?php

declare(strict_types=1);

namespace Munus\Collection;

use Munus\Control\Option;
use Munus\Value;

/**
 * @template T
 * @template-extends Value<T>
 */
abstract class Traversable extends Value
{
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

        if (get_class($object) !== get_class($this)) {
            return false;
        }

        $iterator1 = $object->iterator();
        $iterator2 = $this->iterator();

        while ($iterator1->hasNext() && $iterator2->hasNext()) {
            if ($iterator1->next() != $iterator2->next()) {
                return false;
            }
        }

        return $iterator1->hasNext() === $iterator2->hasNext();
    }
}
