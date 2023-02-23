<?php

/**
 * This class is generated using generate-tuples script.
 * Do not change it manually! Modify generator and use above script.
 */

declare(strict_types=1);

namespace Munus\Tuple;

use Munus\Tuple;

/**
 * @template T1
 * @template T2
 * @template T3
 * @template T4
 * @template T5
 * @template T6
 * @template T7
 */
class Tuple7 extends Tuple
{
    private const SIZE = 7;

    /**
     * @param T1 $value1
     * @param T2 $value2
     * @param T3 $value3
     * @param T4 $value4
     * @param T5 $value5
     * @param T6 $value6
     * @param T7 $value7
     */
    public function __construct(
        private $value1,
        private $value2,
        private $value3,
        private $value4,
        private $value5,
        private $value6,
        private $value7,
    ) {
    }

    public function arity(): int
    {
        return self::SIZE;
    }

    public function toArray(): array
    {
        return [
            $this->value1,
            $this->value2,
            $this->value3,
            $this->value4,
            $this->value5,
            $this->value6,
            $this->value7,
        ];
    }

    /**
     * @template T
     *
     * @param T $value
     *
     * @returns Tuple8<T, T1, T2, T3, T4, T5, T6, T7>
     */
    public function prepend($value): Tuple8
    {
        return new Tuple8($value, $this->value1, $this->value2, $this->value3, $this->value4, $this->value5, $this->value6, $this->value7);
    }

    /**
     * @template T
     *
     * @param T $value
     *
     * @returns Tuple8<T1, T2, T3, T4, T5, T6, T7, T>
     */
    public function append($value): Tuple8
    {
        return new Tuple8($this->value1, $this->value2, $this->value3, $this->value4, $this->value5, $this->value6, $this->value7, $value);
    }

    /**
     * @param Tuple0 $tuple
     *
     * @returns Tuple7<T1, T2, T3, T4, T5, T6, T7>
     */
    public function concatTuple0($tuple)
    {
        return $this->concat($tuple);
    }

    /**
     * @template U1
     *
     * @param Tuple1<U1> $tuple
     *
     * @returns Tuple8<T1, T2, T3, T4, T5, T6, T7, U1>
     */
    public function concatTuple1($tuple)
    {
        return $this->concat($tuple);
    }
}
