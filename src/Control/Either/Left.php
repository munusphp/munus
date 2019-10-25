<?php

declare(strict_types=1);

namespace Munus\Control\Either;

use Munus\Control\Either;

/**
 * @template L
 * @template R
 * @template-extends Either<L,R>
 */
final class Left extends Either
{
    /**
     * @var L
     */
    private $value;

    /**
     * @param L $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function isLeft(): bool
    {
        return true;
    }

    public function isRight(): bool
    {
        return false;
    }

    /**
     * @return R
     */
    public function get()
    {
        throw new \BadMethodCallException('get() on Left');
    }

    /**
     * @return L
     */
    public function getLeft()
    {
        return $this->value;
    }
}
