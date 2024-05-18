<?php

declare(strict_types=1);

namespace Munus\Collection;

use Munus\Collection\Iterator\ArrayIterator;

/**
 * @template T
 *
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
     * @template U
     *
     * @param U ...$elements
     *
     * @return Set<U>
     */
    public static function of(...$elements): self
    {
        return new self($elements);
    }

    /**
     * @template U
     *
     * @param U[] $elements
     *
     * @return Set<U>
     */
    public static function ofAll(array $elements): self
    {
        return new self($elements);
    }

    public function length(): int
    {
        return count($this->elements);
    }

    /**
     * @param T $element
     *
     * @return Set<T>
     */
    public function add($element): self
    {
        if ($this->contains($element)) {
            return $this;
        }

        $elements = $this->elements;
        $elements[] = $element;

        return self::fromPointer($elements);
    }

    /**
     * @param Set<T> $elements
     *
     * @return Set<T>
     */
    public function addAll(Set $elements): self
    {
        $new = $this->elements;
        foreach ($elements->elements as $current) {
            if (!$this->contains($current)) {
                $new[] = $current;
            }
        }

        return self::fromPointer($new);
    }

    /**
     * @param T $element
     *
     * @return Set<T>
     */
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

    /**
     * @param Set<T> $elements
     *
     * @return Set<T>
     */
    public function removeAll(Set $elements): self
    {
        $new = [];
        foreach ($this->elements as $current) {
            if (!$elements->contains($current)) {
                $new[] = $current;
            }
        }

        return self::fromPointer($new);
    }

    /**
     * @param Set<T> $set
     *
     * @return Set<T>
     */
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

    /**
     * @param Set<T> $set
     *
     * @return Set<T>
     */
    public function diff(Set $set): self
    {
        $diff = array_diff($this->elements, $set->elements);

        return self::fromPointer($diff);
    }

    public function intersect(Set $set): self
    {
        $intersect = array_intersect($this->elements, $set->elements);

        return self::fromPointer($intersect);
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

        $element = current($this->elements);

        if ($element === false) {
            throw new \RuntimeException('Set is empty');
        }

        return $element;
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
     * @param callable(T):U $mapper
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

    public function flatMap(callable $mapper)
    {
        $mapped = [];
        $iterator = $this->iterator();
        while ($iterator->hasNext()) {
            $mappedIterator = $mapper($iterator->next())->iterator();
            while ($mappedIterator->hasNext()) {
                $mapped[] = $mappedIterator->next();
            }
        }

        return new self($mapped);
    }

    /**
     * @param callable(T):bool $predicate
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
    public function sorted()
    {
        $elements = $this->elements;
        asort($elements);

        return self::fromPointer($elements);
    }

    /**
     * @param callable(T):bool $predicate
     *
     * @return Set<T>
     */
    public function dropWhile(callable $predicate)
    {
        $elements = $this->elements;
        while ($elements !== [] && $predicate(current($elements)) === true) {
            unset($elements[key($elements)]);
        }

        return self::fromPointer($elements);
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

    /**
     * @return Set<T>
     */
    public function drop(int $n)
    {
        if ($n <= 0) {
            return $this;
        }

        if ($n >= $this->length()) {
            return self::empty();
        }

        $sliced = array_slice($this->elements, $n);

        return self::fromPointer($sliced);
    }
}
