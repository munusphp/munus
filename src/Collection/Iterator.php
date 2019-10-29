<?php

declare(strict_types=1);

namespace Munus\Collection;

/**
 * @template T
 */
class Iterator
{
    /**
     * @var Traversable<T>
     */
    private $traversable;

    /**
     * @param Traversable<T> $traversable
     */
    public function __construct(Traversable $traversable)
    {
        $this->traversable = $traversable;
    }

    public function hasNext(): bool
    {
        return !$this->traversable->isEmpty();
    }

    /**
     * @return T
     */
    public function next()
    {
        $result = $this->traversable->head();
        $this->traversable = $this->traversable->tail();

        return $result;
    }
}
