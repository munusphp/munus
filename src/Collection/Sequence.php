<?php

declare(strict_types=1);

namespace Munus\Collection;

use Munus\Value\Comparator;

/**
 * Sequence - immutable sequential data structures.
 *
 * @template T
 *
 * @template-extends Traversable<T>
 */
abstract class Sequence extends Traversable
{
    /**
     * @param T $element
     *
     * @return self<T>
     */
    abstract public function append($element);

    /**
     * @param Traversable<T> $elements
     *
     * @return self<T>
     */
    abstract public function appendAll(Traversable $elements);

    /**
     * @param T $element
     *
     * @return self<T>
     */
    abstract public function prepend($element);

    /**
     * @param Traversable<T> $elements
     *
     * @return self<T>
     */
    abstract public function prependAll(Traversable $elements);

    /**
     * Returns the index of the first occurrence of the given element or -1 if this does not contain the given element.
     *
     * @param T $element
     */
    final public function indexOf($element): int
    {
        $sequence = $this;
        $index = 0;
        while (!$sequence->isEmpty()) {
            if (Comparator::equals($element, $sequence->head())) {
                return $index;
            }
            ++$index;
            $sequence = $sequence->tail();
        }

        return -1;
    }
}
