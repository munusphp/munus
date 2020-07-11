<?php

declare(strict_types=1);

namespace Munus\Collection;

use Munus\Collection\Iterator\MapIterator;
use Munus\Control\Option;
use Munus\Exception\NoSuchElementException;
use Munus\Tuple;
use Munus\Value\Comparator;

/**
 * Immutable Map.
 *
 * @template K
 * @template V
 * @extends Traversable<V>
 */
final class Map extends Traversable
{
    /**
     * @var array<string,V>
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
     * @template U
     *
     * @param array<string,U> $array
     *
     * @return Map<string,U>
     */
    public static function fromArray(array $array): self
    {
        $map = [];
        foreach ($array as $key => $value) {
            $map[(string) $key] = $value;
        }

        return self::fromPointer($map);
    }

    /**
     * @template U
     *
     * @param array<string,U> $map
     *
     * @return Map<string,U>
     */
    private static function fromPointer(array &$map): self
    {
        $newMap = new self();
        $newMap->map = $map;

        return $newMap;
    }

    /**
     * @return Option<V>
     */
    public function get(): Option
    {
        if (func_get_args() === []) {
            return $this->isEmpty() ? Option::none() : Option::of($this->head()[1]);
        }
        $key = func_get_arg(0);

        return isset($this->map[$key]) ? Option::some($this->map[$key]) : Option::none();
    }

    /**
     * @param V $value
     *
     * @return Map<string,V>
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

    public function head(): Tuple
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException('head of empty Map');
        }

        $key = array_key_first($this->map);

        return Tuple::of($key, $this->map[$key]);
    }

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
     * @param callable(Tuple<V>): Tuple<U> $mapper
     *
     * @return Map<string,U>
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
     * @param callable(string):string $keyMapper
     *
     * @return Map<string,V>
     */
    public function mapKeys(callable $keyMapper): self
    {
        $map = [];
        foreach ($this->map as $key => $value) {
            $map[$keyMapper($key)] = $value;
        }

        return self::fromPointer($map);
    }

    /**
     * @template U
     *
     * @param callable(V):U $valueMapper
     *
     * @return Map<string,U>
     */
    public function mapValues(callable $valueMapper): self
    {
        $map = [];
        foreach ($this->map as $key => $value) {
            $map[$key] = $valueMapper($value);
        }

        return self::fromPointer($map);
    }

    /**
     * @param callable(Tuple):bool $predicate
     *
     * @return Map<string,V>
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
     * @param callable(Tuple):bool $predicate
     *
     * @return Map<string,V>
     */
    public function dropWhile(callable $predicate)
    {
        $map = $this->map;
        while ($map !== [] && $predicate(Tuple::of(key($map), current($map))) === true) {
            unset($map[key($map)]);
        }

        return self::fromPointer($map);
    }

    /**
     * Take n next entries of map.
     *
     * @return Map<string,V>
     */
    public function take(int $n)
    {
        if ($n >= $this->length()) {
            return $this;
        }

        $map = array_slice($this->map, 0, $n, true);

        return self::fromPointer($map);
    }

    /**
     * Drop n next entries of map.
     *
     * @return Map<string,V>
     */
    public function drop(int $n)
    {
        if ($n <= 0) {
            return $this;
        }

        if ($n >= $this->length()) {
            return self::empty();
        }

        $map = array_slice($this->map, $n, null, true);

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

    /**
     * @return Stream<V>
     */
    public function values(): Stream
    {
        return Stream::ofAll(array_values($this->map));
    }

    /**
     * @return Set<string>
     */
    public function keys(): Set
    {
        return Set::ofAll(array_keys($this->map));
    }

    /**
     * Default contains() method will search for Tuple of key and value.
     *
     * @param Tuple $element
     */
    public function contains($element): bool
    {
        return $this->get($element[0])->map(function ($value) use ($element) {
            return Comparator::equals($value, $element[1]);
        })->getOrElse(false);
    }

    public function containsKey(string $key): bool
    {
        return isset($this->map[$key]);
    }

    /**
     * @param V $value
     */
    public function containsValue($value): bool
    {
        foreach ($this->map as $v) {
            if (Comparator::equals($v, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     *  If collisions occur, the value of this map is taken.
     */
    public function merge(self $map): self
    {
        if ($this->isEmpty()) {
            return $map;
        }

        if ($map->isEmpty()) {
            return $this;
        }

        return $map->fold($this, function (Map $result, Tuple $entry) {
            return !$result->containsKey($entry[0]) ? $result->put($entry[0], $entry[1]) : $result;
        });
    }
}
