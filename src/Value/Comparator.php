<?php

declare(strict_types=1);

namespace Munus\Value;

use Munus\Value;

final class Comparator
{
    public static function equals($a, $b): bool
    {
        if ($a instanceof Value && $b instanceof Value) {
            return $a->equals($b);
        }

        if (is_object($a) && is_object($b)) {
            return $a == $b;
        }

        return $a === $b;
    }
}
