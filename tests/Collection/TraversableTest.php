<?php

declare(strict_types=1);

namespace Munus\Tests\Collection;

use Munus\Collection\GenericList;
use Munus\Collection\Map;
use Munus\Collection\Set;
use Munus\Collection\Stream;
use Munus\Control\Option;
use Munus\Exception\UnsupportedOperationException;
use Munus\Tuple;
use PHPUnit\Framework\TestCase;

final class TraversableTest extends TestCase
{
    public function testStrictEquals(): void
    {
        self::assertFalse(
            GenericList::of(1, 2, 3)->equals(GenericList::of(1, '2', 3))
        );
    }

    public function testTraversableImplementationAgnosticEquals(): void
    {
        self::assertTrue(
            GenericList::of(1, 2, 3)->equals(Stream::of(1, 2, 3))
        );
        self::assertTrue(
            Set::of('a', 'b')->equals(GenericList::ofAll(['a', 'b']))
        );
    }

    public function testTraversableSum(): void
    {
        self::assertSame(6, Set::of(1, 2, 3)->sum());
        self::assertSame(6.6, Set::of(1.1, 2.2, 3.3)->sum());
        self::assertSame(6.4, Set::of(1.1, 2, 3.3)->sum());
        self::assertSame(-6.6, Set::of(-1.1, -2.2, -3.3)->sum());
        self::assertEqualsWithDelta(-2.2, Set::of(-1.1, 2.2, -3.3)->sum(), 0.01);
        self::assertSame(6, Set::of(1, '2', 3)->sum());
        self::assertSame(6.3, Set::of(1, '2', '3.3')->sum());
        self::assertSame(0, GenericList::empty()->sum());
    }

    public function testTraversableSumNonNumeric(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        Set::empty()->add('error')->sum();
    }

    public function testTraversableProduct(): void
    {
        self::assertSame(6, Set::of(1, 2, 3)->product());
        self::assertEqualsWithDelta(7.986, Set::of(1.1, 2.2, 3.3)->product(), 0.001);
        self::assertSame(7.26, Set::of(1.1, 2, 3.3)->product());
        self::assertEqualsWithDelta(-7.986, Set::of(-1.1, -2.2, -3.3)->product(), 0.001);
        self::assertEqualsWithDelta(7.986, Set::of(-1.1, 2.2, -3.3)->product(), 0.001);
        self::assertSame(6, Set::of(1, '2', 3)->product());
        self::assertSame(6.6, Set::of(1, '2', '3.3')->product());
        self::assertSame(1, GenericList::empty()->product());
    }

    public function testTraversableProductNonNumeric(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        GenericList::empty()->append('error')->product();
    }

    public function testTraversableAverage(): void
    {
        self::assertEquals(2.0, Set::of(1, 2, 3)->average());
        self::assertEquals(1.0, Set::of(1, 1, 1)->average());
        self::assertEqualsWithDelta(7.66, Set::of(3, 9, 11)->average(), 0.01);
    }

    public function testAverageOnEmpty(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        Set::empty()->average();
    }

    public function testAverageOnNonNumeric(): void
    {
        $this->expectException(UnsupportedOperationException::class);

        Set::of(5, 6, new \stdClass())->average();
    }

    public function testMin(): void
    {
        self::assertEquals(Option::of(1), Set::of(3, 2, 1)->min());
        self::assertEquals(Option::of(-1.11), Set::of(3.34, 2.21, -1.11)->min());
        self::assertEquals(Option::of('a'), Set::of('a', 'b', 'c')->min());
        self::assertEquals(Option::of('a'), Set::of('x', 'y', 'z', 'a')->min());
        self::assertEquals(Option::none(), Set::empty()->min());
    }

    public function testMax(): void
    {
        self::assertEquals(Option::of(3), Set::of(3, 2, 1)->max());
        self::assertEquals(Option::of(3.34), Set::of(3.34, 2.21, -1.11)->max());
        self::assertEquals(Option::of('c'), Set::of('a', 'b', 'c')->max());
        self::assertEquals(Option::of('z'), Set::of('x', 'y', 'z', 'a')->max());
        self::assertEquals(Option::none(), Set::empty()->max());
    }

    public function testCount(): void
    {
        self::assertEquals(0, Set::empty()->count(function ($value) {return false; }));
        self::assertEquals(2, Set::of(1, 2, 3, 4)->count(function (int $value) {return $value % 2 === 0; }));
        self::assertEquals(0, Set::of(1, 2, 3, 4)->count(function (int $value) {return $value % 7 === 0; }));

        self::assertEquals(1, Set::of('munus', 'is', 'awesome')->count(function (string $value) {return strpos($value, 'some') !== false; }));
    }

    public function testAnyMatch(): void
    {
        self::assertTrue(GenericList::of(1, 2, 3, 4, 5)->anyMatch(fn (int $v) => $v === 1));
        self::assertTrue(GenericList::of(1, 2, 3, 4, 5)->anyMatch(fn (int $v) => $v === 3));
        self::assertTrue(GenericList::of(1, 2, 3, 4, 5)->anyMatch(fn (int $v) => $v === 5));

        self::assertFalse(GenericList::of(1, 2, 3, 4, 5)->anyMatch(fn (int $v) => $v === 0));
        self::assertFalse(GenericList::empty()->anyMatch(fn (int $v) => $v === 0));
    }

    public function testAllMatch(): void
    {
        self::assertTrue(GenericList::of(2, 4, 6, 8)->allMatch(fn (int $v) => $v % 2 === 0));
        self::assertTrue(GenericList::of(10, 12, 14, 16)->allMatch(fn (int $v) => $v % 2 === 0));
        self::assertTrue(GenericList::empty()->allMatch(fn (int $v) => $v % 2 === 1));

        self::assertFalse(GenericList::of(2, 4, 6, 8)->allMatch(fn (int $v) => $v % 2 === 1));
        self::assertFalse(GenericList::of(0)->allMatch(fn (int $v) => $v % 2 === 1));
    }

    public function testNoneMatch(): void
    {
        self::assertTrue(GenericList::of(2, 4, 6, 8)->noneMatch(fn (int $v) => $v % 2 === 1));
        self::assertTrue(GenericList::empty()->noneMatch(fn (int $v) => $v % 2 === 1));

        self::assertFalse(GenericList::of(2, 4, 6, 8)->noneMatch(fn (int $v) => $v % 2 === 0));
        self::assertFalse(GenericList::of(1, 2, 3)->noneMatch(fn (int $v) => $v % 2 === 0));
        self::assertFalse(GenericList::of(1)->noneMatch(fn (int $v) => $v % 2 === 1));
    }

    public function testFindFirstWhenEmpty(): void
    {
        self::assertTrue(GenericList::empty()->findFirst()->isEmpty());
        self::assertTrue(Map::empty()->findFirst()->isEmpty());
        self::assertTrue(Set::empty()->findFirst()->isEmpty());
        self::assertTrue(Stream::empty()->findFirst()->isEmpty());
    }

    public function testFindFirstWhenNotEmpty(): void
    {
        self::assertSame('a', GenericList::of('a', 'b', 'c')->findFirst()->get());
        self::assertEquals(Tuple::of('a', 'b'), Map::fromArray(['a' => 'b', 'c' => 'd'])->findFirst()->get());
        self::assertSame('a', Set::of('a', 'b', 'c')->findFirst()->get());
        self::assertSame('a', Stream::of('a', 'b', 'c')->findFirst()->get());
        self::assertSame('a', Stream::iterate('a', fn () => 'a')->findFirst()->get());
    }
}
