<?php

declare(strict_types=1);

namespace Munus\Collection\GenericList;

use Munus\Collection\GenericList;
use Munus\Exception\NoSuchElementException;

/**
 * @template T
 *
 * @extends GenericList<T>
 */
final class Nil extends GenericList
{
    private function __construct()
    {
    }

    /**
     * @return self<T>
     */
    public static function instance(): self
    {
        return new self();
    }

    public function length(): int
    {
        return 0;
    }

    public function isEmpty(): bool
    {
        return true;
    }

    /**
     * @throws NoSuchElementException
     */
    public function head()
    {
        throw new NoSuchElementException('head of empty list');
    }

    /**
     * @throws NoSuchElementException
     */
    public function tail()
    {
        throw new NoSuchElementException('tail of empty list');
    }
}
