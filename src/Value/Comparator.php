<?php

declare(strict_types=1);

namespace Munus\Value;

final class Comparator
{
    public static function equals($a, $b): bool
    {
        if (is_object($a) && is_object($b)) {
            return $a == $b;
        }

        return $a === $b;
    }
}
