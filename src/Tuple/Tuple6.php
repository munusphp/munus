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
 */
class Tuple6 extends Tuple
{
    /**
     * @param T1 $value1
     * @param T2 $value2
     * @param T3 $value3
     * @param T4 $value4
     * @param T5 $value5
     * @param T6 $value6
     */
    public function __construct(
        private $value1,
        private $value2,
        private $value3,
        private $value4,
        private $value5,
        private $value6,
    ) {
    }

    public function arity(): int
    {
        return 6;
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
        ];
    }

    /**
     * @template T
     *
     * @param T $value
     *
     * @returns Tuple7<T, T1, T2, T3, T4, T5, T6>
     */
    public function prepend($value): Tuple7
    {
        return new Tuple7($value, $this->value1, $this->value2, $this->value3, $this->value4, $this->value5, $this->value6);
    }

    /**
     * @template T
     *
     * @param T $value
     *
     * @returns Tuple7<T1, T2, T3, T4, T5, T6, T>
     */
    public function append($value): Tuple7
    {
        return new Tuple7($this->value1, $this->value2, $this->value3, $this->value4, $this->value5, $this->value6, $value);
    }

    /**
     * @param Tuple0 $tuple
     *
     * @returns Tuple6<T1, T2, T3, T4, T5, T6>
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
     * @returns Tuple7<T1, T2, T3, T4, T5, T6, U1>
     */
    public function concatTuple1($tuple)
    {
        return $this->concat($tuple);
    }

    /**
     * @template U1
     * @template U2
     *
     * @param Tuple2<U1, U2> $tuple
     *
     * @returns Tuple8<T1, T2, T3, T4, T5, T6, U1, U2>
     */
    public function concatTuple2($tuple)
    {
        return $this->concat($tuple);
    }
}
