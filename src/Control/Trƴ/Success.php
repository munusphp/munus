<?php

declare(strict_types=1);

namespace Munus\Control\Trƴ;

use Munus\Control\Trƴ;

/**
 * @template T
 * @template-extends Trƴ<T>
 */
final class Success extends Trƴ
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

    public function getCause(): \Throwable
    {
        throw new \BadMethodCallException('getCause() on Success');
    }

    public function isEmpty(): bool
    {
        return false;
    }
}
