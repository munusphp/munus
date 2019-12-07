<?php

declare(strict_types=1);

namespace Munus;

use Munus\Exception\UnsupportedOperationException;
use Munus\Value\Comparator;

final class Tuple implements \ArrayAccess
{
    /**
     * @var \SplFixedArray
     */
    private $data;

    private function __construct(array $data)
    {
        $this->data = \SplFixedArray::fromArray(array_values($data), false);
    }

    public static function of(...$values): self
    {
        if ($values === []) {
            throw new \InvalidArgumentException('At least on value in Tuple is required');
        }

        return new self($values);
    }

    public function arity(): int
    {
        return $this->data->count();
    }

    public function toArray(): array
    {
        return $this->data->toArray();
    }

    public function append($value): self
    {
        return new self(array_merge($this->data->toArray(), [$value]));
    }

    public function concat(self $tuple): self
    {
        return new self(array_merge($this->data->toArray(), $tuple->data->toArray()));
    }

    /**
     * @template U
     *
     * @param callable:U $transformer
     *
     * @return U
     */
    public function apply(callable $transformer)
    {
        return call_user_func($transformer, ...$this->data->toArray());
    }

    public function map(callable $mapper): self
    {
        return self::of(...array_map($mapper, $this->data->toArray()));
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new UnsupportedOperationException('cannot change Tuple value with ArrayAccess');
    }

    public function offsetUnset($offset)
    {
        throw new UnsupportedOperationException('cannot unset Tuple value');
    }

    public function equals(self $tuple): bool
    {
        foreach ($this->data as $key => $value) {
            if (!Comparator::equals($value, $tuple[$key])) {
                return false;
            }
        }

        return true;
    }
}
