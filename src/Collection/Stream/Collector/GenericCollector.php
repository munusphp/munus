<?php

declare(strict_types=1);

namespace Munus\Collection\Stream\Collector;

use Munus\Collection\Stream\Collector;

/**
 * @template T
 * @template R
 *
 * @implements Collector<T,R>
 */
final class GenericCollector implements Collector
{
    /**
     * @var R
     */
    private $supplier;

    /**
     * @var callable(R,T):R
     */
    private $accumulator;

    /**
     * @var callable(R):R
     */
    private $finisher;

    /**
     * @param R               $supplier
     * @param callable(R,T):R $accumulator
     * @param callable(R):R   $finisher
     */
    public function __construct($supplier, callable $accumulator, callable $finisher)
    {
        $this->supplier = $supplier;
        $this->accumulator = $accumulator;
        $this->finisher = $finisher;
    }

    /**
     * @template U
     * @template W
     *
     * @param W               $supplier
     * @param callable(W,U):W $accumulator
     *
     * @return self<U,W>
     */
    public static function of($supplier, callable $accumulator): self
    {
        /** @var self<U,W> $instance */
        $instance = new self($supplier, $accumulator, fn ($s) => $s);

        return $instance;
    }

    /**
     * @param T $value
     */
    public function accumulate($value): void
    {
        $this->supplier = ($this->accumulator)($this->supplier, $value);
    }

    /**
     * @return R
     */
    public function finish()
    {
        return ($this->finisher)($this->supplier);
    }
}
