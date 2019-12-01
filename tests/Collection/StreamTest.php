<?php

declare(strict_types=1);

namespace Munus\Tests\Collection;

use Munus\Collection\Stream;
use Munus\Control\Option;
use Munus\Lazy;
use PHPUnit\Framework\TestCase;

final class StreamTest extends TestCase
{
    public function testStreamIterator(): void
    {
        $iterator = Stream::ofAll([1, 2, 3])->iterator();

        self::assertTrue($iterator->hasNext());
        self::assertEquals(1, $iterator->next());
        self::assertEquals(2, $iterator->next());
        self::assertEquals(3, $iterator->next());
        self::assertFalse($iterator->hasNext());
    }

    public function testStreamFind(): void
    {
        self::assertEquals(Option::of('munus'), Stream::ofAll(['lambda', 'munus', 'function'])->find(function ($name) {
            return $name === 'munus';
        }));
        self::assertEquals(Option::of(null), Stream::ofAll(['lambda', 'missing', 'function'])->find(function ($name) {
            return $name === 'munus';
        }));
    }

    public function testStreamMap(): void
    {
        self::assertTrue(
            Stream::ofAll([1, 2, 3])->map(function (int $int): int {
                return $int * 2;
            })->equals(Stream::ofAll([2, 4, 6]))
        );
    }

    public function testStreamForEach(): void
    {
        $counter = 0;
        Stream::range(1, 3)->forEach(function (int $x) use (&$counter) {
            self::assertEquals(++$counter, $x);
        });
        self::assertEquals(3, $counter);
    }

    public function testStreamReduce(): void
    {
        self::assertEquals(10, Stream::of(1, 2, 3, 4)->reduce(function (int $a, int $b): int {return $a + $b; }));
    }

    public function testStreamFold(): void
    {
        self::assertEquals(14, Stream::of(
            Lazy::of(function () {return 'Munus'; }),
            Lazy::of(function () {return 'is'; }),
            Lazy::of(function () {return 'awesome'; })
        )->fold(0, function (int $a, Lazy $b): int {
            return $a + mb_strlen($b->get());
        }));
    }

    public function testStreamContains(): void
    {
        self::assertTrue(Stream::ofAll([1, 2, 3])->contains(2));
    }

    public function testStreamMapSingle(): void
    {
        self::assertTrue(
            Stream::of(6)->map(function (int $int): int {
                return $int * 6;
            })->equals(Stream::ofAll([36]))
        );
    }

    public function testStreamRange(): void
    {
        self::assertTrue(
            Stream::range(1, 5)->equals(Stream::ofAll([1, 2, 3, 4, 5]))
        );
    }

    public function testStreamTake(): void
    {
        self::assertTrue(Stream::of(1, 2, 3)->equals(Stream::of(1, 2, 3)->take(3)));
        self::assertTrue(Stream::of(1, 2, 3)->equals(Stream::of(1, 2, 3)->take(4)));
        self::assertTrue(Stream::empty()->equals(Stream::empty()->take(3)));
        self::assertTrue(Stream::of(1, 2, 3)->equals(Stream::of(1, 2, 3, 4)->take(3)));
        self::assertTrue(Stream::range(1, 3)->equals(Stream::range(1, 10)->take(3)));
    }
}
