<?php

declare(strict_types=1);

namespace Munus\Collection\Iterator;

use Munus\Collection\Iterator;
use Munus\Exception\NoSuchElementException;
use Munus\Tuple\Tuple2;

/**
 * @template K
 * @template V
 *
 * @template-extends Iterator<Tuple2<K, V>>
 */
final class MapIterator extends Iterator
{
    /**
     * @var array<Tuple2<K,V>>
     */
    private array $map;

    /**
     * @param array<Tuple2<K,V>> $map
     */
    public function __construct(array $map)
    {
        $this->map = $map;
        reset($this->map);
    }

    public function key(): mixed
    {
        return current($this->map)[0];
    }

    public function current(): mixed
    {
        return current($this->map)[1];
    }

    public function rewind(): void
    {
        reset($this->map);
    }

    /**
     * @throws NoSuchElementException
     *
     * @return Tuple2<K, V>
     */
    public function next(): Tuple2
    {
        if (!$this->valid()) {
            throw new NoSuchElementException();
        }
        $next = current($this->map);
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

    /**
     * @return array<Tuple2<K, V>>
     */
    public function toArray(): array
    {
        return $this->map;
    }
}
