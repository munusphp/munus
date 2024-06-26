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
 */
class Tuple3 extends Tuple
{
    /**
     * @param T1 $value1
     * @param T2 $value2
     * @param T3 $value3
     */
    public function __construct(
        private $value1,
        private $value2,
        private $value3,
    ) {
    }

    public function arity(): int
    {
        return 3;
    }

    /**
     * @return array{T1, T2, T3}
     */
    public function toArray(): array
    {
        return [
            $this->value1,
            $this->value2,
            $this->value3,
        ];
    }

    /**
     * @template T
     *
     * @param T $value
     *
     * @return Tuple4<T, T1, T2, T3>
     */
    public function prepend($value): Tuple4
    {
        return new Tuple4($value, $this->value1, $this->value2, $this->value3);
    }

    /**
     * @template T
     *
     * @param T $value
     *
     * @return Tuple4<T1, T2, T3, T>
     */
    public function append($value): Tuple4
    {
        return new Tuple4($this->value1, $this->value2, $this->value3, $value);
    }

    /**
     * @param Tuple0 $tuple
     *
     * @return Tuple3<T1, T2, T3>
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
     * @return Tuple4<T1, T2, T3, U1>
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
     * @return Tuple5<T1, T2, T3, U1, U2>
     */
    public function concatTuple2($tuple)
    {
        return $this->concat($tuple);
    }

    /**
     * @template U1
     * @template U2
     * @template U3
     *
     * @param Tuple3<U1, U2, U3> $tuple
     *
     * @return Tuple6<T1, T2, T3, U1, U2, U3>
     */
    public function concatTuple3($tuple)
    {
        return $this->concat($tuple);
    }

    /**
     * @template U1
     * @template U2
     * @template U3
     * @template U4
     *
     * @param Tuple4<U1, U2, U3, U4> $tuple
     *
     * @return Tuple7<T1, T2, T3, U1, U2, U3, U4>
     */
    public function concatTuple4($tuple)
    {
        return $this->concat($tuple);
    }

    /**
     * @template U1
     * @template U2
     * @template U3
     * @template U4
     * @template U5
     *
     * @param Tuple5<U1, U2, U3, U4, U5> $tuple
     *
     * @return Tuple8<T1, T2, T3, U1, U2, U3, U4, U5>
     */
    public function concatTuple5($tuple)
    {
        return $this->concat($tuple);
    }
}
