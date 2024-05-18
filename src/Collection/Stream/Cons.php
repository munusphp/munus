<?php

declare(strict_types=1);

namespace Munus\Collection\Stream;

use Munus\Collection\Iterator;
use Munus\Collection\Iterator\CompositeIterator;
use Munus\Collection\Iterator\StreamIterator;
use Munus\Collection\Stream;
use Munus\Collection\Traversable;
use Munus\Lazy;

/**
 * Non-empty Stream, consisting of a head and tail.
 *
 * @template T
 *
 * @extends Stream<T>
 */
final class Cons extends Stream
{
    /**
     * @var T
     */
    private $head;

    /**
     * @var Lazy<Stream<T>>
     */
    private $tail;

    /**
     * @param T                    $head
     * @param callable():Stream<T> $tail
     */
    public function __construct($head, callable $tail)
    {
        $this->head = $head;
        $this->tail = Lazy::of($tail);
    }

    public function length(): int
    {
        return $this->fold(0, /** @param T $ignored */ function (int $n, $ignored): int {return $n + 1; });
    }

    /**
     * @return T
     */
    public function head()
    {
        return $this->head;
    }

    /**
     * @return Stream<T>
     */
    public function tail()
    {
        return $this->tail->get();
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function iterator(): Iterator
    {
        return new StreamIterator($this);
    }

    /**
     * @return Stream<T>
     */
    public function append($element)
    {
        return new Cons($this->head, function () use ($element) {
            return $this->tail()->append($element);
        });
    }

    /**
     * @return Stream<T>
     */
    public function appendAll(Traversable $elements)
    {
        if ($elements->isEmpty()) {
            return $this;
        }

        return Stream::ofAll(CompositeIterator::of($this->iterator(), $elements->iterator()));
    }
}
