<?php

declare(strict_types=1);

namespace Munus\Control;

use Munus\Collection\Iterator;
use Munus\Control\Either\Left;
use Munus\Control\Either\Right;
use Munus\Value;

/**
 * @template L
 * @template R
 * @template-extends Value<R>
 */
abstract class Either extends Value
{
    private function __construct()
    {
    }

    final public static function left($left): self
    {
        return new Left($left);
    }

    final public static function right($right): self
    {
        return new Right($right);
    }

    abstract public function isLeft(): bool;

    abstract public function isRight(): bool;

    /**
     * @return R
     */
    abstract public function get();

    /**
     * @return L
     */
    abstract public function getLeft();

    final public function isSingleValued(): bool
    {
        return true;
    }

    final public function isEmpty(): bool
    {
        return $this->isLeft();
    }

    /**
     * @template U
     *
     * @param callable(T): U $mapper
     *
     * @return Either<U>
     */
    public function map(callable $mapper)
    {
        if ($this->isRight()) {
            return new Right($mapper($this->get()));
        }

        return $this;
    }

    public function iterator(): Iterator
    {
        return $this->isRight() ? Iterator::of($this->get()) : Iterator::empty();
    }
}
