<?php

declare(strict_types=1);

namespace Munus\Control\TryTo;

use Munus\Control\TryTo;
use Munus\Exception\NoSuchElementException;

/**
 * @template T
 *
 * @template-extends TryTo<T>
 */
final class Success extends TryTo
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

    public function isSuccess(): bool
    {
        return true;
    }

    public function isFailure(): bool
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

    /**
     * @throws NoSuchElementException
     */
    public function getCause(): \Throwable
    {
        throw new NoSuchElementException('getCause() on Success');
    }

    public function isEmpty(): bool
    {
        return false;
    }
}
