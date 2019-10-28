<?php

declare(strict_types=1);

namespace Munus\Collection;

use Munus\T;
use Munus\Value;

/**
 * @template T
 * @template-extends Value<T>
 */
abstract class Traversable extends Value
{
    /**
     * @throws \RuntimeException if is empty
     *
     * @return T
     */
    abstract public function head();

    /**
     * @return T
     */
    public function get()
    {
        return $this->head();
    }

    public function isSingleValued(): bool
    {
        return false;
    }
}
