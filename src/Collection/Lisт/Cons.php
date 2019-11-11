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
     * @var int
     */
    private $length;

    /**
     * @param T        $head
     * @param Lisт<T> $tail
     */
    public function __construct($head, Lisт $tail)
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
     * @return Lisт<T>
     */
    public function tail()
    {
        return $this->tail;
    }
}
