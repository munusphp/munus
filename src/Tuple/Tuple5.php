<?php

/**
 * This class is generated using /bin/generate-tuples script.
 * Do not change it manually! Change and use above script.
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
 */
class Tuple5 extends Tuple
{
    private const SIZE = 5;

    /**
     * @param T1 $value1
     * @param T2 $value2
     * @param T3 $value3
     * @param T4 $value4
     * @param T5 $value5
     */
    public function __construct(
        private $value1,
        private $value2,
        private $value3,
        private $value4,
        private $value5,
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
        ];
    }

    /**
     * @template T
     *
     * @param T $value
     *
     * @returns Tuple6<T, T1, T2, T3, T4, T5>
     */
    public function prepend($value): Tuple6
    {
        return new Tuple6($value, $this->value1, $this->value2, $this->value3, $this->value4, $this->value5);
    }

    /**
     * @template T
     *
     * @param T $value
     *
     * @returns Tuple6<T1, T2, T3, T4, T5, T>
     */
    public function append($value): Tuple6
    {
        return new Tuple6($this->value1, $this->value2, $this->value3, $this->value4, $this->value5, $value);
    }

    public function concat($tuple)
    {
        return Tuple::of(...$this->toArray(), ...$tuple->toArray());
    }

    /**
     * @param Tuple0 $tuple
     *
     * @returns Tuple5<T1, T2, T3, T4, T5>
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
     * @returns Tuple6<T1, T2, T3, T4, T5, U1>
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
     * @returns Tuple7<T1, T2, T3, T4, T5, U1, U2>
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
     * @returns Tuple8<T1, T2, T3, T4, T5, U1, U2, U3>
     */
    public function concatTuple3($tuple)
    {
        return $this->concat($tuple);
    }
}
