<?php

declare(strict_types=1);

namespace Munus\Collection;

use Munus\Collection\GenericList\Cons;
use Munus\Collection\GenericList\Nil;

/**
 * @template T
 * @template-extends Traversable<T>
 */
abstract class GenericList extends Traversable
{
    /**
     * @param array<T> $elements
     *
     * @return GenericList<T>
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
     * @param array<T> $elements
     *
     * @return GenericList<T>
     */
    public static function ofAll(array $elements): self
    {
        $list = Nil::instance();
        foreach ($elements as $element) {
            $list = $list->prepend($element);
        }

        return $list->reverse();
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

    /**
     * @param callable<T>:bool $predicate
     *
     * @return GenericList<T>
     */
    public function filter(callable $predicate)
    {
        if ($this->isEmpty()) {
            return $this;
        }

        /** @var GenericList $filtered */
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
    public function take(int $n)
    {
        if ($n <= 0) {
            return self::empty();
        }
        if ($n >= $this->length()) {
            return $this;
        }
        $result = self::empty();
        $list = $this;
        for ($i = 0; $i < $n; $i++,$list = $list->tail()) {
            $result = $result->prepend($list->head());
        }

        return $result->reverse();
    }

    /**
     * @param T $element
     *
     * @return GenericList<T>
     */
    public function prepend($element)
    {
        return new Cons($element, $this);
    }

    /**
     * @param T $element
     *
     * @return GenericList<T>
     */
    public function append($element)
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
    public function reverse()
    {
        $list = Nil::instance();
        $iterator = $this->iterator();
        while ($iterator->hasNext()) {
            $list = $list->prepend($iterator->next());
        }

        return $list;
    }
}
