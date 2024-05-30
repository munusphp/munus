<?php

declare(strict_types=1);

namespace Munus\Tests\Stub;

use Munus\Value\Comparable;

final class Event implements Comparable, \Stringable
{
    public function __construct(public string $id, public string $name)
    {
    }

    public function equals(mixed $other): bool
    {
        return self::class === $other::class && $this->name === $other->name;
    }

    public function __toString(): string
    {
        return $this->id;
    }
}
