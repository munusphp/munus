<?php

declare(strict_types=1);

namespace Munus\Tests\Collection\Stream;

use Munus\Collection\GenericList;
use Munus\Collection\Map;
use Munus\Collection\Set;
use Munus\Collection\Stream;
use Munus\Collection\Stream\Collectors;
use PHPUnit\Framework\TestCase;

final class CollectorsTest extends TestCase
{
    public function testToListCollector(): void
    {
        $list = Stream::from(1)->take(5)->collect(Collectors::toList());

        self::assertTrue(GenericList::of(1, 2, 3, 4, 5)->equals($list));
    }

    public function testToSetCollector(): void
    {
        $set = Stream::from(1)->take(5)->collect(Collectors::toSet());

        self::assertTrue(Set::of(1, 2, 3, 4, 5)->equals($set));
    }

    public function testToMapWithDefaultValueMapperCollector(): void
    {
        $map = Stream::from(1)->take(3)->collect(Collectors::toMap(
            fn (int $value): string => 'a'.$value
        ));

        self::assertTrue(Map::fromArray(['a1' => 1, 'a2' => 2, 'a3' => 3])->equals($map));
    }

    public function testToMapCollector(): void
    {
        $map = Stream::from(1)->take(3)->collect(Collectors::toMap(
            fn (int $value): string => 'a'.$value,
            fn (int $value): int => $value * 2,
        ));

        self::assertTrue(Map::fromArray(['a1' => 2, 'a2' => 4, 'a3' => 6])->equals($map));
    }

    public function testSummingCollector(): void
    {
        self::assertEquals(15, Stream::from(1)->take(5)->collect(Collectors::summing()));
        self::assertEquals(15, Stream::of('1', '2', '3', '4', '5')->collect(Collectors::summing()));

        $this->expectException(\InvalidArgumentException::class);

        Stream::of(1, 'not number')->collect(Collectors::summing());
    }

    public function testJoiningCollector(): void
    {
        $stream = Stream::ofAll(str_split('Munus'));
        self::assertEquals('Munus', $stream->collect(Collectors::joining()));
        self::assertEquals('M u n u s', $stream->collect(Collectors::joining(' ')));
    }

    public function testCountingCollector(): void
    {
        self::assertEquals(3, Stream::of('a', 'b', 'c')->collect(Collectors::counting()));
        self::assertEquals(30, Stream::range(1, 30)->collect(Collectors::counting()));
    }

    public function testAveragingCollector(): void
    {
        self::assertEquals(2, Stream::of(1, 2, 3)->collect(Collectors::averaging()));
        self::assertEquals(4.75, Stream::of(5, 5, 4, 5)->collect(Collectors::averaging()));

        $this->expectException(\InvalidArgumentException::class);

        Stream::of(1, 'not number')->collect(Collectors::averaging());
    }
}
