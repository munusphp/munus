<?php

declare(strict_types=1);

namespace Munus;

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
     * @param T $other
     *
     * @return T
     */
    public function getOrElse($other)
    {
        return $this->isEmpty() ? $other : $this->get();
    }

    public function equals($object): bool
    {
        if (is_object($object)) {
            return $this->get() == $object;
        }

        return $this->get() === $object;
    }
}
