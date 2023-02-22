<?php

declare(strict_types=1);

namespace Munus\Collection;

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
}
