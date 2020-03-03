<?php

declare(strict_types=1);

namespace Munus\Control;

use Munus\Collection\Iterator;
use Munus\Control\Option\None;
use Munus\Control\Option\Some;
use Munus\Value;

/**
 * @template T
 * @extends Value<T>
 */
abstract class Option extends Value
{
    /**
     * @template U
     *
     * @param ?U $value
     *
     * @return Option<U>
     */
    public static function of($value): self
    {
        return $value === null ? self::none() : self::some($value);
    }

    /**
     * @template U
     *
     * @param U $value
     *
     * @return Option<U>
     */
    public static function some($value): self
    {
        return new Some($value);
    }

    /**
     * @phpstan-return Option<T>
     * @psalm-return Option<none>
     */
    public static function none(): self
    {
        return new None();
    }

    /**
     * @template U
     *
     * @param U $value
     *
     * @return Option<U>
     */
    public static function when(bool $condition, $value): self
    {
        return $condition === true ? self::some($value) : self::none();
    }

    public function isPresent(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * @template U
     *
     * @param callable(T):U $mapper
     *
     * @return Option<U>
     */
    public function map(callable $mapper)
    {
        return $this->isEmpty() ? self::none() : self::some($mapper($this->get()));
    }

    public function isSingleValued(): bool
    {
        return true;
    }

    /**
     * @return Iterator<T>
     */
    public function iterator(): Iterator
    {
        return $this->isEmpty() ? Iterator::empty() : Iterator::of($this->get());
    }
}
