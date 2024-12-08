<?php

declare(strict_types=1);

namespace Munus\Collection\Iterator;

use Munus\Collection\Iterator;
use Munus\Exception\NoSuchElementException;

/**
 * @template T
 *
 * @template-extends Iterator<T>
 */
final class SingletonIterator extends Iterator
{
    /**
     * @var T
     */
    private $element;

    private bool $hasNext;

    /**
     * @param T $element
     */
    public function __construct($element)
    {
        $this->element = $element;
        $this->hasNext = true;
    }

    public function hasNext(): bool
    {
        return $this->hasNext;
    }

    /**
     * @throws NoSuchElementException
     *
     * @return T
     */
    public function next()
    {
        if (!$this->hasNext()) {
            throw new NoSuchElementException();
        }
        $this->hasNext = false;

        return $this->element;
    }

    /**
     * @throws NoSuchElementException
     */
    public function current(): mixed
    {
        if ($this->hasNext === true) {
            return $this->element;
        }

        throw new NoSuchElementException();
    }

    public function rewind(): void
    {
        $this->hasNext = true;
    }

    public function key(): mixed
    {
        return 0;
    }
}
