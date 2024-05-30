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
final class Failure extends TryTo
{
    /**
     * @var \Throwable
     */
    private $cause;

    public function __construct(\Throwable $cause)
    {
        $this->cause = $cause;
    }

    public function isSuccess(): bool
    {
        return false;
    }

    public function isFailure(): bool
    {
        return true;
    }

    /**
     * @throws NoSuchElementException
     *
     * @return T
     */
    public function get()
    {
        throw new NoSuchElementException('get() on Failure');
    }

    public function getCause(): \Throwable
    {
        return $this->cause;
    }

    public function isEmpty(): bool
    {
        return true;
    }

    public function equals(mixed $object): bool
    {
        return $this->cause == $object;
    }
}
