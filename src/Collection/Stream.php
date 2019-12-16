<?php

declare(strict_types=1);

namespace Munus\Collection;

use Munus\Collection\Stream\Collector;
use Munus\Collection\Stream\Cons;
use Munus\Collection\Stream\EmptyStream;
use Munus\Value;

/**
 * @template T
 * @extends Traversable<T>
 */
abstract class Stream extends Traversable
{
    /**
     * @param array<int,T> $elements
     *
     * @return Stream<T>
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
     * @return Stream<int>
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
     * @return Cons<int>
     */
    public static function from(int $value): self
    {
        return new Cons($value, function () use ($value) {
            return self::from($value + 1);
        });
    }

    /**
     * Create infinitely long Stream using a function to calculate the next value
     * based on the previous.
     *
     * @param callable():T $supplier
     *
     * @return Cons<T>
     */
    public static function continually(callable $supplier): self
    {
        return new Cons($supplier(), function () use ($supplier) {
            return self::continually($supplier);
        });
    }

    /**
     * @param T             $seed
     * @param callable(T):T $iterator
     *
     * @return Cons<T>
     */
    public static function iterate($seed, callable $iterator): self
    {
        $current = $iterator($seed);

        return new Cons($current, function () use ($iterator, $current) {
            return self::iterate($current, $iterator);
        });
    }

    /**
     * Constructs a Stream of a head element and a tail supplier.
     *
     * @param T            $head
     * @param callable():T $supplier
     *
     * @return Cons<T>
     */
    public static function cons($head, callable $supplier): self
    {
        return new Cons($head, function () use ($supplier) {
            $next = $supplier();
            if ($next instanceof EmptyStream) {
                return $next;
            }

            return self::cons($next, $supplier);
        });
    }

    /**
     * @throws \RuntimeException if is empty
     *
     * @return Stream<T>
     */
    abstract public function tail();

    /**
     * @param callable(T):void $action
     *
     * @return Stream<T>
     */
    public function peek(callable $action): self
    {
        if ($this->isEmpty()) {
            return $this;
        }

        $head = $this->head();
        $action($head);

        return new Cons($head, function () use ($action) {
            return $this->tail()->peek($action);
        });
    }

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

    /**
     * @param callable(T):bool $predicate
     *
     * @return Stream<T>
     */
    public function filter(callable $predicate)
    {
        if ($this->isEmpty()) {
            return $this;
        }

        $stream = $this;
        while (!$stream->isEmpty() && !$predicate($stream->head())) {
            $stream = $stream->tail();
        }

        $finalStream = $stream;

        return $stream->isEmpty() ? self::empty() : new Cons($stream->head(), function () use ($finalStream, $predicate) {
            return $finalStream->tail()->filter($predicate);
        });
    }

    /**
     * @return Stream<T>
     */
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

    /**
     * @template R
     *
     * @param Collector<T,R> $collector
     *
     * @return R
     */
    public function collect(Collector $collector)
    {
        $iterator = $this->iterator();
        while ($iterator->hasNext()) {
            $collector->accumulate($iterator->next());
        }

        return $collector->finish();
    }
}
