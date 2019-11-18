<?php

declare(strict_types=1);

namespace Munus\Control\TryEx;

use Munus\Control\TryEx;

/**
 * @template T
 * @template-extends TryEx<T>
 */
final class Failure extends TryEx
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
