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
 */
class Tuple2 extends Tuple
{
    private const SIZE = 2;

    /**
     * @param T1 $value1
     * @param T2 $value2
     */
    public function __construct(
        private $value1,
        private $value2,
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
        ];
    }

    /**
     * @template T
     *
     * @param T $value
     *
     * @returns Tuple3<T, T1, T2>
     */
    public function prepend($value): Tuple3
    {
        return new Tuple3($value, $this->value1, $this->value2);
    }

    /**
     * @template T
     *
     * @param T $value
     *
     * @returns Tuple3<T1, T2, T>
     */
    public function append($value): Tuple3
    {
        return new Tuple3($this->value1, $this->value2, $value);
    }

    /**
     * @param Tuple0 $tuple
     *
     * @returns Tuple2<T1, T2>
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
     * @returns Tuple3<T1, T2, U1>
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
     * @returns Tuple4<T1, T2, U1, U2>
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
     * @returns Tuple5<T1, T2, U1, U2, U3>
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
     * @returns Tuple6<T1, T2, U1, U2, U3, U4>
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
     * @returns Tuple7<T1, T2, U1, U2, U3, U4, U5>
     */
    public function concatTuple5($tuple)
    {
        return $this->concat($tuple);
    }

    /**
     * @template U1
     * @template U2
     * @template U3
     * @template U4
     * @template U5
     * @template U6
     *
     * @param Tuple6<U1, U2, U3, U4, U5, U6> $tuple
     *
     * @returns Tuple8<T1, T2, U1, U2, U3, U4, U5, U6>
     */
    public function concatTuple6($tuple)
    {
        return $this->concat($tuple);
    }
}
