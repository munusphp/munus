<?php

declare(strict_types=1);

namespace Munus;

use Munus\Functiοn\Supplier;

/**
 * @template T
 * @extends Value<T>
 * @implements Supplier<T>
 */
final class Lazy extends Value implements Supplier
{
    /**
     * @var T
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
     * @param callable():T $supplier
     *
     * @return Lazy<T>
     */
    public static function of(callable $supplier): self
    {
        return new self($supplier);
    }

    /**
     * @param T $value
     *
     * @return Lazy<T>
     */
    public static function ofValue($value)
    {
        return new self(function () use ($value) {
            return $value;
        });
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
