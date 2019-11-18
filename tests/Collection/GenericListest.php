<?php

declare(strict_types=1);

namespace Munus\Tests\Collection;

use Munus\Collection\GenericList;
use Munus\Collection\Stream;
use PHPUnit\Framework\TestCase;

final class GenericListest extends TestCase
{
    public function testListReverse(): void
    {
        self::assertTrue(
            GenericList::ofAll([1, 2, 3])->reverse()->equals(GenericList::ofAll([3, 2, 1]))
        );
    }

    public function testListLength(): void
    {
        self::assertEquals(3, GenericList::ofAll([1, 2, 3])->length());
        self::assertEquals(0, GenericList::empty()->length());
        self::assertEquals(4, GenericList::ofAll([1, 2, 3])->prepend(0)->length());
    }

    public function testListMap(): void
    {
        self::assertTrue(
            GenericList::ofAll([1, 2, 3])->map(function (int $int): int {
                return $int * 2;
            })->equals(GenericList::ofAll([2, 4, 6]))
        );
    }

    public function testListMapSingle(): void
    {
        self::assertTrue(
            GenericList::of(6)->map(function (int $int): int {
                return $int * 6;
            })->equals(GenericList::of(36))
        );
    }

    public function testListToStream(): void
    {
        self::assertTrue(
            GenericList::ofAll([1, 2, 3])->toStream()->equals(Stream::ofAll([1, 2, 3]))
        );
    }

    public function testListOfMultipleParames(): void
    {
        self::assertTrue(
            GenericList::of('a', 'b', 'c')->equals(GenericList::ofAll(['a', 'b', 'c']))
        );
    }

    public function testListAppend(): void
    {
        self::assertTrue(
            GenericList::of('a', 'b', 'c')->equals(GenericList::of(['a', 'b'])->append('c'))
        );
    }
}
