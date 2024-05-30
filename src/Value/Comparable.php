<?php

declare(strict_types=1);

namespace Munus\Value;

interface Comparable
{
    public function equals(mixed $other): bool;
}
