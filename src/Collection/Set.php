<?php

declare(strict_types=1);

namespace Munus\Collection;

use Munus\Collection\Iterator\ArrayIterator;

/**
 * @template T
 * @extends Traversable<T>
 */
final class Set extends Traversable
{
    /**
     * @var T[]
     */
    private $elements = [];

    private function __construct(array $elements = [])
    {
        foreach ($elements as $element) {
            if ($this->contains($element)) {
                continue;
            }

            $this->elements[] = $element;
        }
    }

    private static function fromPointer(array &$elements): self
    {
        $set = new self();
        $set->elements = $elements;

        return $set;
    }

    public static function empty(): self
    {
        return new self();
    }

    /**
     * @param T[] ...$elements
     */
    public static function of(...$elements): self
    {
        return new self($elements);
    }

    /**
     * @param T[] $elements
     */
    public static function ofAll(array $elements): self
    {
        return new self($elements);
    }

    public function length(): int
    {
        return count($this->elements);
    }

    public function add($element): self
    {
        if ($this->contains($element)) {
            return $this;
        }

        $elements = $this->elements;
        $elements[] = $element;

        return self::fromPointer($elements);
    }

    public function remove($element): self
    {
        if (!$this->contains($element)) {
            return $this;
        }

        $elements = [];
        foreach ($this->elements as $current) {
            if ($current !== $element) {
                $elements[] = $current;
            }
        }

        return self::fromPointer($elements);
    }

    public function union(Set $set): self
    {
        if ($set->isEmpty()) {
            return $this;
        }

        if ($this->isEmpty()) {
            return $set;
        }

        $elements = $this->elements;
        foreach ($set->elements as $element) {
            if ($this->contains($element)) {
                continue;
            }

            $elements[] = $element;
        }

        return self::fromPointer($elements);
    }

    public function isEmpty(): bool
    {
        return $this->elements === [];
    }

    public function iterator(): Iterator
    {
        return new ArrayIterator($this->elements);
    }

    public function head()
    {
        reset($this->elements);

        return current($this->elements);
    }

    public function tail()
    {
        $tail = $this->iterator()->toArray();
        unset($tail[0]);

        return new self($tail);
    }

    /**
     * @template U
     *
     * @param callable<T>:U $mapper
     *
     * @return Set<U>
     */
    public function map(callable $mapper)
    {
        $mapped = [];
        $iterator = $this->iterator();
        while ($iterator->hasNext()) {
            $mapped[] = $mapper($iterator->next());
        }

        return self::fromPointer($mapped);
    }

    /**
     * @param callable<T>:bool $predicate
     *
     * @return Set<T>
     */
    public function filter(callable $predicate)
    {
        $filtered = array_filter($this->elements, $predicate);

        return self::fromPointer($filtered);
    }

    /**
     * @return Set<T>
     */
    public function take(int $n)
    {
        if ($n <= 0) {
            return self::empty();
        }
        if ($n >= $this->length()) {
            return $this;
        }

        $sliced = array_slice($this->elements, 0, $n);

        return self::fromPointer($sliced);
    }
}
