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
     * @template U
     *
     * @param callable():U $supplier
     *
     * @return TryTo<U>
     */
    public static function run(callable $supplier): self
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

    /**
     * @template U
     *
     * @param callable(\Throwable):U $recovery
     *
     * @return TryTo<U>
     */
    public function recover(string $throwable, callable $recovery): self
    {
        if ($this->isFailure()) {
            $cause = $this->getCause();
            if ($cause instanceof $throwable) {
                return self::run(function () use ($cause, $recovery) {
                    return $recovery($cause);
                });
            }
        }

        return $this;
    }

    public function andThen(callable $callable): self
    {
        if ($this->isFailure()) {
            return $this;
        }

        try {
            $callable($this->get());

            return $this;
        } catch (\Throwable $throwable) {
            return new Failure($throwable);
        }
    }

    public function andFinally(callable $callable): self
    {
        try {
            $callable();

            return $this;
        } catch (\Throwable $throwable) {
            return new Failure($throwable);
        }
    }

    public function onSuccess(callable $consumer): self
    {
        if ($this->isSuccess()) {
            $consumer($this->get());
        }

        return $this;
    }

    public function onFailure(callable $consumer): self
    {
        if ($this->isFailure()) {
            $consumer($this->getCause());
        }

        return $this;
    }

    public function onSpecificFailure(string $throwable, callable $consumer): self
    {
        if ($this->isFailure()) {
            $cause = $this->getCause();
            if ($cause instanceof $throwable) {
                $consumer($this->getCause());
            }
        }

        return $this;
    }

    abstract public function isSuccess(): bool;

    abstract public function isFailure(): bool;

    /**
     * @return T
     */
    abstract public function get();

    abstract public function getCause(): \Throwable;
}
