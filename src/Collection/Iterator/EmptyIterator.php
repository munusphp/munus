<?php

declare(strict_types=1);

namespace Munus\Collection\Iterator;

use Munus\Collection\Iterator;
use Munus\Exception\NoSuchElementException;

final class EmptyIterator extends Iterator
{
    private function __construct()
    {
    }

    public static function instance(): self
    {
        return new self();
    }

    public function hasNext(): bool
    {
        return false;
    }

    public function next(): void
    {
        throw new NoSuchElementException();
    }
}
