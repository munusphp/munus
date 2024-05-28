<?php

declare(strict_types=1);

namespace Munus\Tests\Stub;

use Munus\Value\Comparable;

final class Event implements Comparable
{
    public function __construct(public string $id, public string $name)
    {
    }

    public function equals(Comparable $other): bool
    {
        return self::class === $other::class && $this->name === $other->name;
    }
}
