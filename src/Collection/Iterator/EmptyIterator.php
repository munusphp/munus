<?php

declare(strict_types=1);

namespace Munus\Collection\Iterator;

use Munus\Collection\Iterator;

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

    public function next()
    {
        throw new \LogicException('next on EmptyIterator');
    }
}
