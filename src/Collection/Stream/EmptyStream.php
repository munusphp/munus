<?php

declare(strict_types=1);

namespace Munus\Collection\Stream;

use Munus\Collection\Iterator;
use Munus\Collection\Stream;
use Munus\Collection\Traversable;
use Munus\Exception\NoSuchElementException;

/**
 * Empty is better but it is reserved keyword.
 *
 * @template T
 *
 * @extends Stream<T>
 */
final class EmptyStream extends Stream
{
    private function __construct()
    {
    }

    /**
     * @return self<T>
     */
    public static function instance(): self
    {
        return new self();
    }

    public function length(): int
    {
        return 0;
    }

    /**
     * @throws NoSuchElementException
     */
    public function head()
    {
        throw new NoSuchElementException('head of empty stream');
    }

    /**
     * @throws NoSuchElementException
     */
    public function tail()
    {
        throw new NoSuchElementException('tail of empty stream');
    }

    public function isEmpty(): bool
    {
        return true;
    }

    public function iterator(): Iterator
    {
        return Iterator::empty();
    }

    /**
     * @return Stream<T>
     */
    public function append($element)
    {
        return Stream::of($element);
    }

    /**
     * @return Stream<T>
     */
    public function appendAll(Traversable $elements)
    {
        if ($elements->isEmpty()) {
            return $this;
        }

        return Stream::ofAll($elements->iterator());
    }
}
