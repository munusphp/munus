<?php

declare(strict_types=1);

namespace Munus\Collection\Iterator;

use Munus\Collection\Iterator;
use Munus\Exception\NoSuchElementException;

/**
 * @template T
 * @template-extends Iterator<T>
 */
final class CompositeIterator extends Iterator
{
    /**
     * @var ArrayIterator<Iterator<T>>
     */
    private $iterators;

    /**
     * @var Iterator<T>
     */
    private $current;

    /**
     * @param array<int, Iterator<T>> $iterators
     */
    public function __construct(array $iterators)
    {
        $this->iterators = new ArrayIterator($iterators);
        $this->current = $this->iterators->current();
    }

    /**
     * @template U
     *
     * @param array<int, Iterator<U>> $iterators
     *
     * @return Iterator<U>
     */
    public static function of(...$iterators): Iterator
    {
        return new self($iterators);
    }

    public function hasNext(): bool
    {
        if ($this->current->hasNext()) {
            return true;
        }

        if (!$this->iterators->hasNext()) {
            return false;
        }

        $this->current = $this->iterators->next();

        return $this->hasNext();
    }

    public function next()
    {
        if (!$this->hasNext()) {
            throw new NoSuchElementException();
        }

        return $this->current->next();
    }

    public function rewind()
    {
        $this->iterators->rewind();
        $this->current = $this->iterators->current();
    }

    public function current()
    {
        return $this->current->current();
    }
}
