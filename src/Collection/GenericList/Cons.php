<?php

declare(strict_types=1);

namespace Munus\Collection\GenericList;

use Munus\Collection\GenericList;

/**
 * * Non-empty GenericList, consisting of a head and tail.
 *
 * @template T
 *
 * @extends GenericList<T>
 */
final class Cons extends GenericList
{
    /**
     * @var T
     */
    private $head;

    /**
     * @var GenericList<T>
     */
    private $tail;

    /**
     * @var int
     */
    private $length;

    /**
     * @param T              $head
     * @param GenericList<T> $tail
     */
    public function __construct($head, GenericList $tail)
    {
        $this->head = $head;
        $this->tail = $tail;
        $this->length = 1 + $tail->length();
    }

    public function length(): int
    {
        return $this->length;
    }

    public function isEmpty(): bool
    {
        return false;
    }

    /**
     * @return T
     */
    public function head()
    {
        return $this->head;
    }

    /**
     * @return GenericList<T>
     */
    public function tail()
    {
        return $this->tail;
    }
}
