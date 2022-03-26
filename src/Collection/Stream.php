<?php

declare(strict_types=1);

namespace Munus\Collection;

use Munus\Collection\Stream\Collector;
use Munus\Collection\Stream\Cons;
use Munus\Collection\Stream\EmptyStream;

/**
 * @template T
 * @extends Sequence<T>
 */
abstract class Stream extends Sequence
{
    /**
     * @template U
     *
     * @param U ...$elements
     *
     * @return Stream<U>
     */
    public static function of(...$elements): self
    {
        return self::ofAll($elements);
    }

    /**
     * @template U
     *
     * @param iterable<U> $elements
     *
     * @return Stream<U>
     */
    public static function ofAll(iterable $elements): self
    {
        $elements = Iterator::fromIterable($elements);
        if (!$elements->hasNext()) {
            return self::empty();
        }

        return new Cons($elements->next(), function () use ($elements) {
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
     * Create infinitely long Stream using a function.
     *
     * @template U
     *
     * @param callable():U $supplier
     *
     * @return Cons<U>
     */
    public static function continually(callable $supplier): self
    {
        return new Cons($supplier(), function () use ($supplier) {
            return self::continually($supplier);
        });
    }

    /**
     * Create infinitely long Stream using a function to calculate the next value
     * based on the previous.
     *
     * @template U
     *
     * @param U             $seed
     * @param callable(U):U $iterator
     *
     * @return Cons<U>
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
     * @template U
     *
     * @param U $head
     * @param callable():U $supplier
     *
     * @return Cons<U>
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
     * @param callable(T):bool $predicate
     *
     * @return Stream<T>
     */
    public function dropWhile(callable $predicate)
    {
        $stream = $this;
        while (!$stream->isEmpty() && $predicate($stream->head()) === true) {
            /** @var Stream<T> $stream */
            $stream = $stream->tail();
        }

        return $stream;
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
     * @return Stream<T>
     */
    public function drop(int $n)
    {
        $stream = $this;
        while ($n-- > 0 && !$stream->isEmpty()) {
            /** @var Stream<T> $stream */
            $stream = $stream->tail();
        }

        return $stream;
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

    /**
     * @param T $element
     *
     * @return Stream<T>
     */
    public function prepend($element)
    {
        return new Cons($element, function () {return $this; });
    }

    public function prependAll(Traversable $elements)
    {
        if ($elements->isEmpty()) {
            return $this;
        }

        return self::ofAll($elements)->appendAll($this);
    }
}
