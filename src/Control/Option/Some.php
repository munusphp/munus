<?php

declare(strict_types=1);

namespace Munus\Control\Option;

use Munus\Control\Option;

/**
 * @template T
 * @template-extends Option<T>
 */
final class Some extends Option
{
    /**
     * @var T
     */
    private $value;

    /**
     * @param T $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function isEmpty(): bool
    {
        return false;
    }

    /**
     * @return T
     */
    public function get()
    {
        return $this->value;
    }
}
