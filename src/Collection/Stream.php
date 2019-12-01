<?php

declare(strict_types=1);

namespace Munus\Collection;

use Munus\Collection\Stream\Cons;
use Munus\Collection\Stream\EmptyStream;

/**
 * @template T
 * @extends Traversable<T>
 */
abstract class Stream extends Traversable
{
    /**
     * @param array<T> $elements
     *
     * @return Cons<T>
     */
    public static function of(...$elements): self
    {
        return self::ofAll($elements);
    }

    /**
     * @param T[] $elements
     *
     * @return Stream<T>
     */
    public static function ofAll(array $elements): self
    {
        if (current($elements) === false) {
            return self::empty();
        }

        return new Cons(current($elements), function () use ($elements) {
            next($elements);

            return self::ofAll($elements);
        });
    }

    public static function empty(): self
    {
        return EmptyStream::instance();
    }

    /**
     * @return Cons<int>
     */
    public static function range(int $start = 1, ?int $end = null): self
    {
        if ($start === $end) {
            return self::of($start);
        }

        return new Cons($start, function () use ($start, $end) {
            return self::range($start + 1, $end);
        });
    }

    /**
     * @throws \RuntimeException if is empty
     *
     * @return Stream<T>
     */
    abstract public function tail();

    /**
     * @template U
     *
     * @param callable(T):U $mapper
     *
     * @return Stream<U>
     */
    public function map(callable $mapper): self
    {
        if ($this->isEmpty()) {
            return self::empty();
        }

        return new Cons($mapper($this->head()), function () use ($mapper) {return $this->tail()->map($mapper); });
    }

    public function take(int $n)
    {
        if ($n <= 0 || $this->isEmpty()) {
            return self::empty();
        }

        if ($n === 1) {
            new Cons($this->head(), function () {return self::empty(); });
        }

        return new Cons($this->head(), function () use ($n) {
            return $this->tail()->take($n - 1);
        });
    }
}
