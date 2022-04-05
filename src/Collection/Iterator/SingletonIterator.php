<?php

declare(strict_types=1);

namespace Munus\Collection\Iterator;

use Munus\Collection\Iterator;
use Munus\Exception\NoSuchElementException;

/**
 * @template T
 */
final class SingletonIterator extends Iterator
{
    /**
     * @var T
     */
    private $element;

    /**
     * @var bool
     */
    private $hasNext;

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
     * @return T
     */
    #[\ReturnTypeWillChange]
    public function next()
    {
        if (!$this->hasNext()) {
            throw new NoSuchElementException();
        }
        $this->hasNext = false;

        return $this->element;
    }

    public function current(): mixed
    {
        if ($this->hasNext === true) {
            return $this->element;
        }

        throw new NoSuchElementException();
    }
}
