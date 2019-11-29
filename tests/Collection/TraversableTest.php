<?php

declare(strict_types=1);

namespace Munus\Tests\Collection;

use Munus\Collection\GenericList;
use Munus\Collection\Set;
use Munus\Collection\Stream;
use Munus\Control\Option;
use Munus\Exception\UnsupportedOperationException;
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
        self::assertSame(-2.2, Set::of(-1.1, 2.2, -3.3)->sum());
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
        self::assertSame(7.986, Set::of(1.1, 2.2, 3.3)->product());
        self::assertSame(7.26, Set::of(1.1, 2, 3.3)->product());
        self::assertSame(-7.986, Set::of(-1.1, -2.2, -3.3)->product());
        self::assertSame(7.986, Set::of(-1.1, 2.2, -3.3)->product());
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
}
