<?php

declare(strict_types=1);

namespace Munus\Collection\Iterator;

use Munus\Collection\Iterator;
use Munus\Exception\NoSuchElementException;
use Munus\Tuple;

final class MapIterator extends Iterator
{
    /**
     * @var array
     */
    private $map;

    public function __construct(array $map)
    {
        $this->map = $map;
        reset($this->map);
    }

    /**
     * @return int|string|null
     */
    public function key()
    {
        return key($this->map);
    }

    public function current()
    {
        return current($this->map);
    }

    public function rewind()
    {
        reset($this->map);
    }

    /**
     * @return Tuple
     */
    public function next()
    {
        if (!$this->valid()) {
            throw new NoSuchElementException();
        }
        $next = Tuple::of(key($this->map), current($this->map));
        next($this->map);

        return $next;
    }

    public function valid()
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
