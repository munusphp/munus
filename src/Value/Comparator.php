<?php

declare(strict_types=1);

namespace Munus\Value;

final class Comparator
{
    public static function equals(mixed $a, mixed $b): bool
    {
        if ($a instanceof Comparable) {
            return $a->equals($b);
        }

        if ($b instanceof Comparable) {
            return $b->equals($a);
        }

        if (is_object($a) && is_object($b)) {
            return $a == $b;
        }

        return $a === $b;
    }
}
