<?php

declare(strict_types=1);

namespace Munus\Control\Either;

use Munus\Control\Either;
use Munus\Exception\NoSuchElementException;

/**
 * @template L
 * @template R
 *
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
     * @throws NoSuchElementException
     *
     * @return R
     */
    public function get()
    {
        throw new NoSuchElementException('get() on Left');
    }

    /**
     * @return L
     */
    public function getLeft()
    {
        return $this->value;
    }
}
