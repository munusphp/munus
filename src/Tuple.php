<?php

declare(strict_types=1);

namespace Munus;

use Munus\Exception\UnsupportedOperationException;
use Munus\Tuple\Tuple0;
use Munus\Tuple\Tuple1;
use Munus\Tuple\Tuple2;
use Munus\Tuple\Tuple3;
use Munus\Tuple\Tuple4;
use Munus\Tuple\Tuple5;
use Munus\Tuple\Tuple6;
use Munus\Tuple\Tuple7;
use Munus\Tuple\Tuple8;
use Munus\Value\Comparator;

abstract class Tuple implements \ArrayAccess
{
    public const TUPLE_MAX_SIZE = 8;

    /**
     * @param mixed ...$values
     */
    public static function of(...$values)
    {
        return match (count($values)) {
            0 => new Tuple0(),
            1 => new Tuple1(...$values),
            2 => new Tuple2(...$values),
            3 => new Tuple3(...$values),
            4 => new Tuple4(...$values),
            5 => new Tuple5(...$values),
            6 => new Tuple6(...$values),
            7 => new Tuple7(...$values),
            8 => new Tuple8(...$values),
            default => throw new \InvalidArgumentException('Invalid number of elements'),
        };
    }

    abstract public function arity(): int;

    abstract public function toArray(): array;

    public function concat(Tuple0|Tuple1|Tuple2|Tuple3|Tuple4|Tuple5|Tuple6|Tuple7|Tuple8 $tuple): Tuple0|Tuple1|Tuple2|Tuple3|Tuple4|Tuple5|Tuple6|Tuple7|Tuple8
    {
        return Tuple::of(...$this->toArray(), ...$tuple->toArray());
    }

    /**
     * @template U
     *
     * @param callable():U $transformer
     *
     * @return U
     */
    public function apply(callable $transformer)
    {
        return call_user_func($transformer, ...$this->toArray());
    }

    public function map(callable $mapper): self
    {
        return self::of(...array_map($mapper, $this->toArray()));
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->toArray()[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        $data = $this->toArray();
        if (!isset($data[$offset])) {
            throw new \RuntimeException();
        }

        return $data[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new UnsupportedOperationException('cannot change Tuple value with ArrayAccess');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new UnsupportedOperationException('cannot unset Tuple value');
    }

    public function equals(Tuple $tuple): bool
    {
        if ($this->arity() !== $tuple->arity()) {
            return false;
        }

        foreach ($this->toArray() as $key => $value) {
            if (!Comparator::equals($value, $tuple[$key])) {
                return false;
            }
        }

        return true;
    }
}
