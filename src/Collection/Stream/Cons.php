<?php

declare(strict_types=1);

namespace Munus\Collection\Stream;

use Munus\Collection\Stream;

/**
 * @template T
 * @template-extends Stream<T>
 */
final class Cons extends Stream
{
    /**
     * @var T
     */
    private $head;

    /**
     * @var callable
     */
    private $tail;

    /**
     * @param T $head
     */
    public function __construct($head, callable $tail)
    {
        $this->head = $head;
        $this->tail = $tail;
    }

    /**
     * @return T
     */
    public function head()
    {
        return $this->head;
    }

    public function isEmpty(): bool
    {
        return false;
    }
}
