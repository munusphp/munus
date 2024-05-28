<?php

declare(strict_types=1);

namespace Munus\Value;

use Munus\Tuple;

final class Comparator
{
    /**
     * @param mixed $a
     * @param mixed $b
     */
    public static function equals($a, $b): bool
    {
        if ($a instanceof Comparable) {
            return $a->equals($b);
        }

        if ($b instanceof Comparable) {
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
