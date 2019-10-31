<?php

declare(strict_types=1);

namespace Munus\Collection\Stream;

use Munus\Collection\Iterator;
use Munus\Collection\Iterator\StreamIterator;
use Munus\Collection\Stream;
use Munus\Lazy;

/**
 * @template T
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
}
