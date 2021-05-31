<?php

declare(strict_types=1);

namespace Munus\Match\GenericCase;

use Munus\Match\GenericCase;

/**
 * @template T
 * @template U
 */
class GenericCaseCallable extends GenericCase
{
    /**
     * @var callable(T):U
     */
    private $callable;

    /**
     * @param T             $value
     * @param callable(T):U $callable
     */
    public function __construct($value, callable $callable)
    {
        $this->value = $value;
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
