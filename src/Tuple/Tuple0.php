<?php

/**
 * This class is generated using generate-tuples script.
 * Do not change it manually! Modify generator and use above script.
 */

declare(strict_types=1);

namespace Munus\Tuple;

use Munus\Tuple;

class Tuple0 extends Tuple
{
    public function __construct()
    {
    }

    public function arity(): int
    {
        return 0;
    }

    /**
     * @return array{}
     */
    public function toArray(): array
    {
        return [];
    }

    /**
     * @template T
     *
     * @param T $value
     *
     * @return Tuple1<T>
     */
    public function prepend($value): Tuple1
    {
        return new Tuple1($value);
    }

    /**
     * @template T
     *
     * @param T $value
     *
     * @return Tuple1<T>
     */
    public function append($value): Tuple1
    {
        return new Tuple1($value);
    }

    /**
     * @param Tuple0 $tuple
     *
     * @return Tuple0
     */
    public function concatTuple0($tuple)
    {
        return new Tuple0();
    }

    /**
     * @template U1
     *
     * @param Tuple1<U1> $tuple
     *
     * @return Tuple1<U1>
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
     * @return Tuple2<U1, U2>
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
     * @return Tuple3<U1, U2, U3>
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
     * @return Tuple4<U1, U2, U3, U4>
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
     * @return Tuple5<U1, U2, U3, U4, U5>
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
     * @return Tuple6<U1, U2, U3, U4, U5, U6>
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
     * @return Tuple7<U1, U2, U3, U4, U5, U6, U7>
     */
    public function concatTuple7($tuple)
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
     * @template U8
     *
     * @param Tuple8<U1, U2, U3, U4, U5, U6, U7, U8> $tuple
     *
     * @return Tuple8<U1, U2, U3, U4, U5, U6, U7, U8>
     */
    public function concatTuple8($tuple)
    {
        return $this->concat($tuple);
    }
}
