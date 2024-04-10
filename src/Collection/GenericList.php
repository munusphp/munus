<?php

declare(strict_types=1);

namespace Munus\Collection;

use Munus\Collection\GenericList\Cons;
use Munus\Collection\GenericList\Nil;

/**
 * @template T
 *
 * @extends Sequence<T>
 */
abstract class GenericList extends Sequence
{
    /**
     * @template U
     *
     * @param U ...$elements
     *
     * @return GenericList<U>
     */
    public static function of(...$elements): self
    {
        return self::ofAll($elements);
    }

    public static function empty(): self
    {
        return Nil::instance();
    }

    /**
     * @template U
     *
     * @param iterable<U> $elements
     *
     * @return GenericList<U>
     */
    public static function ofAll(iterable $elements): self
    {
        $list = Nil::instance();
        foreach ($elements as $element) {
            $list = $list->prepend($element);
        }

        return $list->reverse();
    }

    /**
     * @return GenericList<int>
     */
    public static function range(int $start, int $end): self
    {
        if ($start === $end) {
            return self::of($start);
        }

        return self::ofAll(range($start, $end));
    }

    /**
     * @template U
     *
     * @param callable(T):U $mapper
     *
     * @return GenericList<U>
     */
    public function map(callable $mapper): self
    {
        if ($this->isEmpty()) {
            return Nil::instance();
        }

        return new Cons($mapper($this->head()), $this->tail()->map($mapper));
    }

    public function flatMap(callable $mapper)
    {
        $list = self::empty();
        foreach ($this->toArray() as $value) {
            foreach ($mapper($value)->toArray() as $mapped) {
                $list = $list->prepend($mapped);
            }
        }

        return $list->reverse();
    }

    /**
     * @param callable(T):bool $predicate
     *
     * @return GenericList<T>
     */
    public function filter(callable $predicate)
    {
        if ($this->isEmpty()) {
            return $this;
        }

        /** @var GenericList<T> $filtered */
        $filtered = $this->fold(self::empty(), function (GenericList $list, $value) use ($predicate) {
            return $predicate($value) === true ? $list->prepend($value) : $list;
        });

        if ($filtered->isEmpty()) {
            return self::empty();
        }

        if ($filtered->length() === $this->length()) {
            return $this;
        }

        return $filtered->reverse();
    }

    /**
     * @return GenericList<T>
     */
    public function sorted()
    {
        return self::ofAll($this->iterator()->sort());
    }

    /**
     * @param callable(T):bool $predicate
     *
     * @return GenericList<T>
     */
    public function dropWhile(callable $predicate)
    {
        $list = $this;
        while (!$list->isEmpty() && $predicate($list->head())) {
            /** @var GenericList<T> $list */
            $list = $list->tail();
        }

        return $list;
    }

    /**
     * @return GenericList<T>
     */
    public function take(int $n)
    {
        if ($n <= 0) {
            return self::empty();
        }
        if ($n >= $this->length()) {
            return $this;
        }
        /** @var GenericList<T> $result */
        $result = self::empty();
        $list = $this;
        for ($i = 0; $i < $n; $i++,$list = $list->tail()) {
            $result = $result->prepend($list->head());
        }

        return $result->reverse();
    }

    /**
     * @return GenericList<T>
     */
    public function drop(int $n)
    {
        if ($n <= 0) {
            return $this;
        }

        if ($n >= $this->length()) {
            return self::empty();
        }

        $list = $this;
        for ($i = 0; $i < $n && !$list->isEmpty(); ++$i) {
            /** @var GenericList<T> $list */
            $list = $list->tail();
        }

        return $list;
    }

    /**
     * @param T $element
     *
     * @return GenericList<T>
     */
    public function prepend($element): self
    {
        return new Cons($element, $this);
    }

    /**
     * @param T $element
     *
     * @return GenericList<T>
     */
    public function append($element): self
    {
        $list = Nil::instance();
        $list = $list->prepend($element);

        $iterator = $this->reverse()->iterator();
        while ($iterator->hasNext()) {
            $list = $list->prepend($iterator->next());
        }

        return $list;
    }

    /**
     * @return GenericList<T>
     */
    public function reverse(): self
    {
        $list = Nil::instance();
        $iterator = $this->iterator();
        while ($iterator->hasNext()) {
            $list = $list->prepend($iterator->next());
        }

        return $list;
    }

    public function appendAll(Traversable $elements)
    {
        if ($elements->isEmpty()) {
            return $this;
        }

        return self::ofAll($elements)->prependAll($this);
    }

    public function prependAll(Traversable $elements)
    {
        if ($this->isEmpty()) {
            return self::ofAll($elements);
        }

        return self::ofAll($elements)->reverse()->fold($this, function (self $list, $element) {return $list->prepend($element); });
    }
}
