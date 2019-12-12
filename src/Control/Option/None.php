<?php

declare(strict_types=1);

namespace Munus\Control\Option;

use Munus\Control\Option;

/**
 * @template T
 * @template-extends Option<T>
 */
final class None extends Option
{
    public function isEmpty(): bool
    {
        return true;
    }

    /**
     * @return T
     */
    public function get()
    {
        throw new \RuntimeException('No value present');
    }

    public function equals($object): bool
    {
        return $object instanceof self;
    }
}
