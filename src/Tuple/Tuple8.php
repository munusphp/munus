<?php

/**
 * This class is generated using generate-tuples script.
 * Do not change it manually! Modify generator and use above script.
 */

declare(strict_types=1);

namespace Munus\Tuple;

use Munus\Exception\UnsupportedOperationException;
use Munus\Tuple;

/**
 * @template T1
 * @template T2
 * @template T3
 * @template T4
 * @template T5
 * @template T6
 * @template T7
 * @template T8
 */
class Tuple8 extends Tuple
{
    private const SIZE = 8;

    /**
     * @param T1 $value1
     * @param T2 $value2
     * @param T3 $value3
     * @param T4 $value4
     * @param T5 $value5
     * @param T6 $value6
     * @param T7 $value7
     * @param T8 $value8
     */
    public function __construct(
        private $value1,
        private $value2,
        private $value3,
        private $value4,
        private $value5,
        private $value6,
        private $value7,
        private $value8,
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
            $this->value8,
        ];
    }

    public function prepend($value)
    {
        throw new UnsupportedOperationException('Can\'t prepend next value. This is biggest possible Tuple');
    }

    public function append($value)
    {
        throw new UnsupportedOperationException('Can\'t append next value. This is biggest possible Tuple');
    }

    /**
     * @param Tuple0 $tuple
     *
     * @returns Tuple8<T1, T2, T3, T4, T5, T6, T7, T8>
     */
    public function concatTuple0($tuple)
    {
        return $this->concat($tuple);
    }
}
