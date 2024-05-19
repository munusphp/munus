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
final class Right extends Either
{
    /**
     * @var R
     */
    private $value;

    /**
     * @param R $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    public function isLeft(): bool
    {
        return false;
    }

    public function isRight(): bool
    {
        return true;
    }

    /**
     * @return R
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * @throws NoSuchElementException
     *
     * @return L
     */
    public function getLeft()
    {
        throw new NoSuchElementException('getLeft() on Right');
    }
}
