<?php

declare(strict_types=1);

namespace Munus\Tests\Collection;

use Munus\Collection\Lisт;
use Munus\Collection\Stream;
use PHPUnit\Framework\TestCase;

final class LisтTest extends TestCase
{
    public function testListReverse(): void
    {
        self::assertTrue(
            Lisт::ofAll([1, 2, 3])->reverse()->equals(Lisт::ofAll([3, 2, 1]))
        );
    }

    public function testListLength(): void
    {
        self::assertEquals(3, Lisт::ofAll([1, 2, 3])->length());
        self::assertEquals(0, Lisт::empty()->length());
        self::assertEquals(4, Lisт::ofAll([1, 2, 3])->prepend(0)->length());
    }

    public function testListMap(): void
    {
        self::assertTrue(
            Lisт::ofAll([1, 2, 3])->map(function (int $int): int {
                return $int * 2;
            })->equals(Lisт::ofAll([2, 4, 6]))
        );
    }

    public function testListMapSingle(): void
    {
        self::assertTrue(
            Lisт::of(6)->map(function (int $int): int {
                return $int * 6;
            })->equals(Lisт::of(36))
        );
    }

    public function testListToStream(): void
    {
        self::assertTrue(
            Lisт::ofAll([1, 2, 3])->toStream()->equals(Stream::ofAll([1, 2, 3]))
        );
    }

    public function testListOfMultipleParames(): void
    {
        self::assertTrue(
            Lisт::of('a', 'b', 'c')->equals(Lisт::ofAll(['a', 'b', 'c']))
        );
    }
}
