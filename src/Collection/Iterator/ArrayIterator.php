<?php

declare(strict_types=1);

namespace Munus\Collection\Iterator;

use Munus\Collection\Iterator;
use Munus\Exception\NoSuchElementException;

/**
 * @template T
 * @template-extends Iterator<T>
 */
final class ArrayIterator extends Iterator
{
    /**
     * @var T[]
     */
    private $elements;

    /**
     * @var int
     */
    private $index;

    /**
     * @param T[] $elements
     */
    public function __construct(array $elements)
    {
        $this->elements = array_values($elements);
        $this->index = 0;
    }

    public function hasNext(): bool
    {
        return count($this->elements) > $this->index;
    }

    public function next()
    {
        if (isset($this->elements[$this->index])) {
            return $this->elements[$this->index++];
        }

        throw new NoSuchElementException();
    }

    public function current()
    {
        if (isset($this->elements[$this->index])) {
            return $this->elements[$this->index];
        }

        throw new NoSuchElementException();
    }
}
