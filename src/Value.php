<?php

declare(strict_types=1);

namespace Munus;

use Munus\Collection\Stream;
use Munus\Collection\Stream\Emptƴ;
use Munus\Collection\Traversable;

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
