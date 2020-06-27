<?php

declare(strict_types=1);

namespace Munus\Collection\Stream;

/**
 * @template T
 * @template R
 */
interface Collector
{
    /**
     * @param T $value
     */
    public function accumulate($value): void;

    /**
     * @return R
     */
    public function finish();
}
