<?php

declare(strict_types=1);

namespace Munus\Collection;

use Munus\Collection\Iterator\MapIterator;
use Munus\Control\Option;
use Munus\Exception\NoSuchElementException;
use Munus\Tuple;

/**
 * @template T
 * @extends Traversable<T>
 */
final class Map extends Traversable
{
    /**
     * @var array<string,T>
     */
    private $map = [];

    private function __construct()
    {
    }

    public static function empty(): self
    {
        return new self();
    }

    /**
     * Creates Map from given array, all keys will be cast to string.
     *
     * @param array<string,T> $array
     *
     * @return Map<T>
     */
    public static function fromArray(array $array): self
    {
        $map = [];
        foreach ($array as $key => $value) {
            $map[(string) $key] = $value;
        }

        return self::fromPointer($map);
    }

    private static function fromPointer(array &$map): self
    {
        $newMap = new self();
        $newMap->map = $map;

        return $newMap;
    }

    /**
     * @return Option<T>
     */
    public function get(): Option
    {
        if (func_get_args() === []) {
            throw new \InvalidArgumentException('get on Map requires $key argument');
        }
        $key = func_get_arg(0);

        return isset($this->map[$key]) ? Option::some($this->map[$key]) : Option::none();
    }

    /**
     * @param T $value
     *
     * @return Map<T>
     */
    public function put(string $key, $value): self
    {
        $map = $this->map;
        $map[$key] = $value;

        return self::fromPointer($map);
    }

    public function remove(string $key): self
    {
        if (!isset($this->map[$key])) {
            return $this;
        }

        $map = $this->map;
        unset($map[$key]);

        return self::fromPointer($map);
    }

    public function length(): int
    {
        return count($this->map);
    }

    /**
     * @return Tuple<string, T>
     */
    public function head(): Tuple
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException('head of empty Map');
        }

        $key = array_key_first($this->map);

        return Tuple::of($key, $this->map[$key]);
    }

    /**
     * @return Tuple<string, T>
     */
    public function tail()
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException('tail of empty Map');
        }

        $key = array_key_last($this->map);

        return Tuple::of($key, $this->map[$key]);
    }

    /**
     * @template U
     *
     * @param callable<Tuple<string, T>>: Tuple<string, U> $mapper
     *
     * @return Map<U>
     */
    public function map(callable $mapper)
    {
        $map = [];
        foreach ($this->map as $key => $value) {
            $mapped = $mapper(Tuple::of($key, $value));
            $map[$mapped[0]] = $mapped[1];
        }

        return self::fromPointer($map);
    }

    /**
     * @param callable<Tuple<string,T>>:bool $predicate
     *
     * @return Map<T>
     */
    public function filter(callable $predicate)
    {
        $map = [];
        foreach ($this->map as $key => $value) {
            if ($predicate(Tuple::of($key, $value)) === true) {
                $map[$key] = $value;
            }
        }

        return self::fromPointer($map);
    }

    /**
     * Take n next entries of map.
     *
     * @return Map<string,T>
     */
    public function take(int $n)
    {
        if ($n >= $this->length()) {
            return $this;
        }

        $map = array_slice($this->map, 0, $n, true);

        return self::fromPointer($map);
    }

    public function isEmpty(): bool
    {
        return $this->length() === 0;
    }

    public function iterator(): Iterator
    {
        return new MapIterator($this->map);
    }
}
