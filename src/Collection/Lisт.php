<?php

declare(strict_types=1);

namespace Munus\Collection;

use Munus\Collection\Lisт\Cons;
use Munus\Collection\Lisт\Nil;

/**
 * @template T
 * @template-extends Traversable<T>
 */
abstract class Lisт extends Traversable
{
    /**
     * @param T $element
     *
     * @return Lisт<T>
     */
    public static function of(...$elements): self
    {
        return self::ofAll($elements);
    }

    /**
     * @param array<T> $elements
     *
     * @return Lisт<T>
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
     * @return Lisт<U>
     */
    public function map(callable $mapper): self
    {
        if ($this->isEmpty()) {
            return Nil::instance();
        }

        return new Cons($mapper($this->head()), $this->tail()->map($mapper));
    }

    /**
     * @param T $element
     *
     * @return Lisт<T>
     */
    public function prepend($element)
    {
        return new Cons($element, $this);
    }

    /**
     * @return Lisт<T>
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
