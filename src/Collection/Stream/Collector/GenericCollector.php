<?php

declare(strict_types=1);

namespace Munus\Collection\Stream\Collector;

use Munus\Collection\Stream\Collector;

/**
 * @template T
 * @template R
 * @implements Collector<T,R>
 */
final class GenericCollector implements Collector
{
    /**
     * @var R
     */
    private $supplier;

    /**
     * @var callable
     */
    private $accumulator;

    /**
     * @var callable
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
     * @param R               $supplier
     * @param callable(R,T):R $accumulator
     *
     * @return self<T,R>
     */
    public static function of($supplier, callable $accumulator): self
    {
        return new self($supplier, $accumulator, function ($supplier) {return $supplier; });
    }

    /**
     * @param T $value
     */
    public function accumulate($value): void
    {
        $this->supplier = call_user_func($this->accumulator, $this->supplier, $value);
    }

    /**
     * @return R
     */
    public function finish()
    {
        return call_user_func($this->finisher, $this->supplier);
    }
}
