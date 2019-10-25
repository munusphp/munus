<?php

declare(strict_types=1);

namespace Munus\Control;

use Munus\Control\Trƴ\Failure;
use Munus\Control\Trƴ\Success;
use Munus\Value;

/**
 * @template T
 * @template-extends Value<T>
 */
abstract class Trƴ extends Value
{
    /**
     * @return Trƴ<T>
     */
    public static function of(callable $supplier)
    {
        try {
            return new Success($supplier());
        } catch (\Throwable $throwable) {
            return new Failure($throwable);
        }
    }

    abstract public function isSuccess(): bool;

    abstract public function isFailure(): bool;

    /**
     * @return T
     */
    abstract public function get();

    abstract public function getCause(): \Throwable;
}
