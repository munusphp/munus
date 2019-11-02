<?php

declare(strict_types=1);

namespace Munus\Control;

use Munus\Control\Option\None;
use Munus\Control\Option\Some;
use Munus\Value;

/**
 * @template T
 * @template-extends Value<T>
 */
abstract class Option extends Value
{
    /**
     * @param ?T $value
     *
     * @return Option<T>
     */
    public static function of($value): self
    {
        return $value === null ? new None() : new Some($value);
    }

    /**
     * @template U
     *
     * @param callable(T): U $mapper
     *
     * @return Option<U>
     */
    public function map(callable $mapper)
    {
        return $this->isEmpty() ? new None() : new Some($mapper($this->get()));
    }

    public function isSingleValued(): bool
    {
        return true;
    }
}
