<?php

declare(strict_types=1);

namespace Munus;

/**
 * @template T
 */
abstract class Value
{
    abstract public function isEmpty(): bool;

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
}
