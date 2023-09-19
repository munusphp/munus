<?php

declare(strict_types=1);

namespace Munus\Collection\Iterator;

use Munus\Collection\Iterator;
use Munus\Collection\Stream;
use Munus\Collection\Stream\Cons;
use Munus\Lazy;

/**
 * @template T
 *
 * @template-extends Iterator<T>
 */
final class StreamIterator extends Iterator
{
    /**
     * @var Lazy<Stream<T>>
     */
    private $current;

    /**
     * @param Stream<T> $current
     */
    public function __construct(Stream $current)
    {
        $this->current = Lazy::ofValue($current);
    }

    /**
     * @return T
     */
    public function current()
    {
        return $this->current->get()->head();
    }

    public function hasNext(): bool
    {
        return !$this->current->get()->isEmpty();
    }

    /**
     * @return T
     */
    public function next()
    {
        if (!$this->hasNext()) {
            throw new \LogicException('next() on empty iterator');
        }
        /** @var Cons<T> $stream */
        $stream = $this->current->get();
        $this->current = Lazy::of(function () use ($stream) {
            return $stream->tail();
        });

        return $stream->head();
    }

    /**
     * Warning: stream can be infinite.
     */
    public function toArray(): array
    {
        $array = [];
        while ($this->hasNext()) {
            $array[] = $this->next();
        }

        return $array;
    }
}
