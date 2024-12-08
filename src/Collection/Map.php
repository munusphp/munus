<?php

declare(strict_types=1);

namespace Munus\Collection;

use Munus\Collection\Iterator\MapIterator;
use Munus\Control\Option;
use Munus\Exception\NoSuchElementException;
use Munus\Exception\UnsupportedOperationException;
use Munus\Tuple;
use Munus\Tuple\Tuple2;
use Munus\Value;
use Munus\Value\Comparator;

/**
 * Immutable Map.
 *
 * @template K
 * @template V
 *
 * @extends Traversable<V>
 *
 * @implements \ArrayAccess<K, V>
 */
final class Map extends Traversable implements \ArrayAccess
{
    /**
     * @var array<Tuple2<K, V>>
     */
    private array $map = [];

    private function __construct()
    {
    }

    /**
     * @return self<K,V>
     */
    public static function empty(): self
    {
        return new self();
    }

    /**
     * @template U
     * @template T
     *
     * @param array<Tuple2<U, T>> $map
     *
     * @return self<U, T>
     */
    public static function from(array $map): self
    {
        $newMap = new self();
        $newMap->map = $map;

        return $newMap;
    }

    /**
     * Creates Map from given array, all keys will be cast to string.
     *
     * @template U
     *
     * @param array<string,U> $array
     *
     * @return self<string,U>
     */
    public static function fromArray(array $array): self
    {
        $map = [];
        foreach ($array as $key => $value) {
            $map[] = new Tuple2((string) $key, $value);
        }

        return self::fromPointer($map);
    }

    /**
     * @template U
     * @template T
     *
     * @param array<Tuple2<U, T>> $map
     *
     * @return self<U, T>
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

        $position = $this->findPosition($key);
        /** @var Option<V> $option */
        $option = $position !== false ? Option::some($this->map[$position][1]) : Option::none();

        return $option;
    }

    /**
     * @param K $key
     * @param V $value
     *
     * @return self<K,V>
     */
    public function put(mixed $key, mixed $value): self
    {
        $map = $this->map;
        $position = $this->findPosition($key);
        if ($position === false) {
            $map[] = new Tuple2($key, $value);
        } else {
            $map[$position] = new Tuple2($key, $value);
        }

        return self::fromPointer($map);
    }

    /**
     * @param K $key
     *
     * @return self<K,V>
     */
    public function remove(mixed $key): self
    {
        $position = $this->findPosition($key);
        if ($position === false) {
            return $this;
        }

        $map = $this->map;
        array_splice($map, $position, 1);

        return self::fromPointer($map);
    }

    public function length(): int
    {
        return count($this->map);
    }

    /**
     * @throws NoSuchElementException
     *
     * @return Tuple2<K, V>
     */
    public function head(): Tuple2
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException('head of empty Map');
        }

        return $this->map[0];
    }

    /**
     * @throws NoSuchElementException
     *
     * @return self<K, V>
     */
    public function tail(): self
    {
        if ($this->isEmpty()) {
            throw new NoSuchElementException('tail of empty Map');
        }

        $map = $this->map;
        array_splice($map, 0, 1);

        return self::fromPointer($map);
    }

    /**
     * @template U
     * @template T
     *
     * @phpstan-param callable(Tuple2<K, V>): Tuple2<U, T> $mapper
     *
     * @return self<U, T>
     */
    public function map(callable $mapper): self
    {
        $map = [];
        foreach ($this->map as $tuple) {
            $map[] = $mapper($tuple);
        }

        return self::fromPointer($map);
    }

    /**
     * @template U
     * @template T
     *
     * @phpstan-param callable(Tuple2<K, V>): Traversable<Tuple2<U, T>> $mapper
     *
     * @return self<U, T>
     */
    public function flatMap(callable $mapper): self
    {
        $map = [];
        foreach ($this->map as $tuple) {
            foreach ($mapper($tuple) as $mapped) {
                $map[] = $mapped;
            }
        }

        return self::fromPointer($map);
    }

    /**
     * @template U
     *
     * @param callable(K):U $keyMapper
     *
     * @return self<U,V>
     */
    public function mapKeys(callable $keyMapper): self
    {
        $map = [];
        foreach ($this->map as $tuple) {
            $map[] = new Tuple2($keyMapper($tuple[0]), $tuple[1]);
        }

        return self::fromPointer($map);
    }

    /**
     * @template U
     *
     * @param callable(V):U $valueMapper
     *
     * @return self<K,U>
     */
    public function mapValues(callable $valueMapper): self
    {
        $map = [];
        foreach ($this->map as $tuple) {
            $map[] = new Tuple2($tuple[0], $valueMapper($tuple[1]));
        }

        return self::fromPointer($map);
    }

    /**
     * @param callable(Tuple2<K, V>):bool $predicate
     *
     * @return self<K,V>
     */
    public function filter(callable $predicate): self
    {
        $map = [];
        foreach ($this->map as $tuple) {
            if ($predicate($tuple) === true) {
                $map[] = $tuple;
            }
        }

        return self::fromPointer($map);
    }

    /**
     * @return self<K,V>
     */
    public function sorted(): self
    {
        $map = $this->map;
        usort($map, fn (Tuple2 $a, Tuple2 $b) => $a[1] <=> $b[1]);

        return self::fromPointer($map);
    }

    /**
     * @param callable(Tuple2<K, V>):bool $predicate
     *
     * @return self<K,V>
     */
    public function dropWhile(callable $predicate): self
    {
        $map = $this->map;
        while ($map !== [] && $predicate(current($map)) === true) {
            unset($map[key($map)]);
        }

        return self::fromPointer($map);
    }

    /**
     * @param callable(Tuple2<K, V>):bool $predicate
     *
     * @return self<K,V>
     */
    public function dropUntil(callable $predicate): self
    {
        return parent::dropUntil($predicate);
    }

    /**
     * @param callable(V): void $action
     *
     * @return self<K,V>
     */
    public function peek(callable $action): self
    {
        if (!$this->isEmpty()) {
            $action($this->get()->get());
        }

        return $this;
    }

    /**
     * Take n next entries of map.
     *
     * @return self<K,V>
     */
    public function take(int $n): self
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
     * @return self<K,V>
     */
    public function drop(int $n): self
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

    /**
     * @return Iterator<Tuple2<K,V>>
     */
    public function iterator(): Iterator
    {
        return new MapIterator($this->map);
    }

    /**
     * @return Stream<V>
     */
    public function values(): Stream
    {
        return Stream::ofAll(array_map(fn (Tuple2 $tuple) => $tuple[1], $this->map));
    }

    /**
     * @return Set<K>
     */
    public function keys(): Set
    {
        return Set::ofAll(array_map(fn (Tuple2 $tuple) => $tuple[0], $this->map));
    }

    /**
     * Default contains() method will search for Tuple of key and value.
     *
     * @param Tuple2<K, V> $element
     */
    public function contains($element): bool
    {
        foreach ($this->map as $tuple) {
            if ($tuple->equals($element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param K $key
     */
    public function containsKey($key): bool
    {
        foreach ($this->map as $tuple) {
            if (Comparator::equals($tuple[0], $key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param V $value
     */
    public function containsValue($value): bool
    {
        foreach ($this->map as $tuple) {
            if (Comparator::equals($tuple[1], $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     *  If collisions occur, the value of this map is taken.
     *
     * @param self<K,V> $map
     *
     * @return self<K,V>
     */
    public function merge(self $map): self
    {
        if ($this->isEmpty()) {
            return $map;
        }

        if ($map->isEmpty()) {
            return $this;
        }

        return $map->fold($this, function (Map $result, Tuple2 $entry) {
            return !$result->containsKey($entry[0]) ? $result->put($entry[0], $entry[1]) : $result; // @phpstan-ignore offsetAccess.notFound, offsetAccess.notFound, offsetAccess.notFound
        });
    }

    /**
     * @param K $offset
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->findPosition($offset) !== false;
    }

    /**
     * @throws NoSuchElementException
     *
     * @return V
     */
    public function offsetGet(mixed $offset): mixed
    {
        $position = $this->findPosition($offset);
        if ($position === false) {
            throw new NoSuchElementException();
        }

        return $this->map[$position][1];
    }

    /**
     * @throws UnsupportedOperationException
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new UnsupportedOperationException();
    }

    /**
     * @throws UnsupportedOperationException
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new UnsupportedOperationException();
    }

    /**
     * Will try to cast all keys to string, may result in data loss if the keys cannot be converted to a unique string.
     *
     * @return array<string, V>
     */
    public function toNativeArray(): array
    {
        $map = [];
        foreach ($this->map as $tuple) {
            $map[(string) $tuple[0]] = $tuple[1];
        }

        return $map;
    }

    /**
     * @param K $key
     */
    private function findPosition(mixed $key): int|false
    {
        foreach ($this->map as $index => $tuple) {
            if (Comparator::equals($tuple[0], $key)) {
                return $index;
            }
        }

        return false;
    }
}
