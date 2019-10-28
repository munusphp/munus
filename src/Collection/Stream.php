<?php

declare(strict_types=1);

namespace Munus\Collection;

use Munus\Collection\Stream\Cons;
use Munus\Collection\Stream\Emptƴ;

/**
 * @template T
 * @template-extends Traversable<T>
 */
abstract class Stream extends Traversable
{
    /**
     * @param T $element
     */
    public static function of($element): self
    {
        return new Cons($element, function () {return new Emptƴ(); });
    }

    /**
     * @param T[] $elements
     */
    public static function ofAll(array $elements): self
    {
        return new Cons(current($elements), function () use ($elements) {return next($elements); });
    }

    public static function range(int $start = 1, ?int $end = null): self
    {
        if ($start === $end) {
            return self::of($start);
        }

        return new Cons($start, function () use ($start, $end) {
            return self::range($start + 1, $end);
        });
    }
}
