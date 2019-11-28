<?php

declare(strict_types=1);

namespace Munus\Tests\Collection;

use Munus\Collection\GenericList;
use Munus\Collection\Set;
use Munus\Collection\Stream;
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
}
