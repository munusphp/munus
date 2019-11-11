<?php

declare(strict_types=1);

namespace Munus\Collection\Stream;

use Munus\Collection\Iterator;
use Munus\Collection\Stream;

/**
 * @template T
 * @extends Stream<T>
 */
final class Emptƴ extends Stream
{
    private function __construct()
    {
    }

    public static function instance(): self
    {
        return new self();
    }

    public function length(): int
    {
        return 0;
    }

    public function head()
    {
        throw new \RuntimeException('head of empty stream');
    }

    public function tail()
    {
        throw new \RuntimeException('tail of empty stream');
    }

    public function isEmpty(): bool
    {
        return true;
    }

    public function iterator(): Iterator
    {
        return Iterator::empty();
    }
}
