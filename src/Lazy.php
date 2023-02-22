<?php

declare(strict_types=1);

namespace Munus;

use Munus\Collection\Iterator;

/**
 * @template T
 *
 * @extends Value<T>
 */
final class Lazy extends Value
{
    /**
     * @var T
     *
     * @psalm-suppress PropertyNotSetInConstructor
     */
    private $value;

    /**
     * @var callable():T|null
     */
    private $supplier;

    /**
     * @param callable():T $supplier
     */
    private function __construct(callable $supplier)
    {
        $this->supplier = $supplier;
    }

    /**
     * @template U
     *
     * @param callable():U $supplier
     *
     * @return Lazy<U>
     */
    public static function of(callable $supplier): self
    {
        return new self($supplier);
    }

    /**
     * @template U
     *
     * @param U $value
     *
     * @return Lazy<U>
     */
    public static function ofValue($value)
    {
        return new self(function () use ($value) {
            return $value;
        });
    }

    /**
     * @template U
     *
     * @param callable(T): U $mapper
     *
     * @return Lazy<U>
     */
    public function map(callable $mapper)
    {
        return self::of(function () use ($mapper) {return $mapper($this->get()); });
    }

    public function isEmpty(): bool
    {
        return false;
    }

    public function isSingleValued(): bool
    {
        return true;
    }

    /**
     * @return T
     */
    public function get()
    {
        return $this->supplier === null ? $this->value : $this->computeValue();
    }

    public function __invoke()
    {
        return $this->get();
    }

    public function iterator(): Iterator
    {
        return Iterator::of($this->get());
    }

    public function isEvaluated(): bool
    {
        return $this->supplier === null;
    }

    /**
     * @return T
     */
    private function computeValue()
    {
        if ($this->supplier !== null) {
            $this->value = ($this->supplier)();
            $this->supplier = null;
        }

        return $this->value;
    }
}
