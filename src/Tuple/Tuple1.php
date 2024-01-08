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
 */
class Tuple1 extends Tuple
{
    /**
     * @param T1 $value1
     */
    public function __construct(
        private $value1,
    ) {
    }

    public function arity(): int
    {
        return 1;
    }

    public function toArray(): array
    {
        return [
            $this->value1,
        ];
    }

    /**
     * @template T
     *
     * @param T $value
     *
     * @returns Tuple2<T, T1>
     */
    public function prepend($value): Tuple2
    {
        return new Tuple2($value, $this->value1);
    }

    /**
     * @template T
     *
     * @param T $value
     *
     * @returns Tuple2<T1, T>
     */
    public function append($value): Tuple2
    {
        return new Tuple2($this->value1, $value);
    }

    /**
     * @param Tuple0 $tuple
     *
     * @returns Tuple1<T1>
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
     * @returns Tuple2<T1, U1>
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
     * @returns Tuple3<T1, U1, U2>
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
     * @returns Tuple4<T1, U1, U2, U3>
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
     * @returns Tuple5<T1, U1, U2, U3, U4>
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
     * @returns Tuple6<T1, U1, U2, U3, U4, U5>
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
     * @returns Tuple7<T1, U1, U2, U3, U4, U5, U6>
     */
    public function concatTuple6($tuple)
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
     * @template U7
     *
     * @param Tuple7<U1, U2, U3, U4, U5, U6, U7> $tuple
     *
     * @returns Tuple8<T1, U1, U2, U3, U4, U5, U6, U7>
     */
    public function concatTuple7($tuple)
    {
        return $this->concat($tuple);
    }
}
