<?php

declare(strict_types=1);

namespace Munus\Collection\GenericList;

use Munus\Collection\GenericList;

/**
 * @template T
 * @extends GenericList<T>
 */
final class Nil extends GenericList
{
    private function __construct()
    {
    }

    /**
     * @template U
     *
     * @return self<U>
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

    public function head()
    {
        throw new \RuntimeException('head of empty list');
    }

    public function tail()
    {
        throw new \RuntimeException('tail of empty list');
    }
}
