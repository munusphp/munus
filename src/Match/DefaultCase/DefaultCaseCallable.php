<?php

declare(strict_types=1);

namespace Munus\Match\DefaultCase;

use Munus\Match\DefaultCase;

/**
 * @template T
 * @template U
 */
class DefaultCaseCallable extends DefaultCase
{
    /**
     * @var callable(T):U
     */
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @param T $value
     *
     * @return U
     */
    public function apply($value)
    {
        return ($this->callable)($value);
    }
}
