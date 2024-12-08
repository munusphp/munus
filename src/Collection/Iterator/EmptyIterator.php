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
final class EmptyIterator extends Iterator
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

    public function hasNext(): bool
    {
        return false;
    }

    /**
     * @throws NoSuchElementException
     *
     * @return never-return
     */
    public function next()
    {
        throw new NoSuchElementException();
    }
}
