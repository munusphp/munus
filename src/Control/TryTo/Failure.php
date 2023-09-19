<?php

declare(strict_types=1);

namespace Munus\Control\TryTo;

use Munus\Control\TryTo;

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
     * @return T
     */
    public function get()
    {
        throw new \BadMethodCallException('get() on Failure');
    }

    public function getCause(): \Throwable
    {
        return $this->cause;
    }

    public function isEmpty(): bool
    {
        return true;
    }

    public function equals($object): bool
    {
        return $this->cause == $object;
    }
}
