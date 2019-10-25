<?php

declare(strict_types=1);

namespace Munus\Control;

use Munus\Value;

/**
 * @template L
 * @template R
 * @template-extends Value<R>
 */
abstract class Either extends Value
{
    abstract public function isLeft(): bool;

    abstract public function isRight(): bool;

    /**
     * @return R
     */
    abstract public function get();

    /**
     * @return L
     */
    abstract public function getLeft();

    public function isEmpty(): bool
    {
        return $this->isLeft();
    }
}
