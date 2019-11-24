<?php

declare(strict_types=1);

namespace Munus\Control;

use Munus\Collection\Iterator;
use Munus\Control\TryTo\Failure;
use Munus\Control\TryTo\Success;
use Munus\Value;

/**
 * @template T
 * @template-extends Value<T>
 */
abstract class TryTo extends Value
{
    /**
     * @return TryTo<T>
     */
    public static function run(callable $supplier)
    {
        try {
            return new Success($supplier());
        } catch (\Throwable $throwable) {
            return new Failure($throwable);
        }
    }

    public function isSingleValued(): bool
    {
        return true;
    }

    /**
     * @template U
     *
     * @param callable(T): U $mapper
     *
     * @return TryTo<U>
     */
    public function map(callable $mapper)
    {
        if ($this->isFailure()) {
            return $this;
        }

        try {
            return new Success($mapper($this->get()));
        } catch (\Throwable $throwable) {
            return new Failure($throwable);
        }
    }

    public function iterator(): Iterator
    {
        return $this->isSuccess() ? Iterator::of($this->get()) : Iterator::empty();
    }

    abstract public function isSuccess(): bool;

    abstract public function isFailure(): bool;

    /**
     * @return T
     */
    abstract public function get();

    abstract public function getCause(): \Throwable;
}
