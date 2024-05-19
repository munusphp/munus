<?php

declare(strict_types=1);

namespace Munus\Collection\Iterator;

use Munus\Collection\Iterator;
use Munus\Exception\NoSuchElementException;
use Munus\Tuple;

final class MapIterator extends Iterator
{
    /**
     * @var mixed[]
     */
    private array $map;

    public function __construct(array $map)
    {
        $this->map = $map;
        reset($this->map);
    }

    public function key(): mixed
    {
        return key($this->map);
    }

    public function current(): mixed
    {
        return current($this->map);
    }

    public function rewind(): void
    {
        reset($this->map);
    }

    public function next(): Tuple
    {
        if (!$this->valid()) {
            throw new NoSuchElementException();
        }
        $next = Tuple::of(key($this->map), current($this->map));
        next($this->map);

        return $next;
    }

    public function valid(): bool
    {
        return key($this->map) !== null;
    }

    public function hasNext(): bool
    {
        return $this->valid();
    }

    public function toArray(): array
    {
        return $this->map;
    }
}
