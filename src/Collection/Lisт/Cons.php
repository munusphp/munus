<?php

declare(strict_types=1);

namespace Munus\Collection\Lisт;

use Munus\Collection\Lisт;
use Munus\Collection\T;

/**
 * @template T
 * @extends Lisт<T>
 */
final class Cons extends Lisт
{
    /**
     * @var T
     */
    private $head;

    /**
     * @var Lisт<T>
     */
    private $tail;

    /**
     * @param T        $head
     * @param Lisт<T> $tail
     */
    public function __construct($head, Lisт $tail)
    {
        $this->head = $head;
        $this->tail = $tail;
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
     * @return Lisт<T>
     */
    public function tail()
    {
        return $this->tail;
    }
}
