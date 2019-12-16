<?php

declare(strict_types=1);

namespace Munus\Value;

use Munus\Tuple;
use Munus\Value;

final class Comparator
{
    /**
     * @param mixed $a
     * @param mixed $b
     */
    public static function equals($a, $b): bool
    {
        if ($a instanceof Value) {
            return $a->equals($b);
        }

        if ($b instanceof Value) {
            return $b->equals($a);
        }

        if ($a instanceof Tuple && $b instanceof Tuple) {
            return $a->equals($b);
        }

        if (is_object($a) && is_object($b)) {
            return $a == $b;
        }

        return $a === $b;
    }
}
