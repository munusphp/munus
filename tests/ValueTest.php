<?php

declare(strict_types=1);

namespace Munus\Tests;

use Munus\Collection\GenericList;
use Munus\Collection\Stream;
use Munus\Control\Option;
use PHPUnit\Framework\TestCase;

final class ValueTest extends TestCase
{
    public function testEqualsStrict(): void
    {
        self::assertFalse(Option::of('1')->equals(Option::of(1)));
        self::assertTrue(Option::of(GenericList::of(1, 2))->equals(Stream::of(1, 2)));
    }
}
