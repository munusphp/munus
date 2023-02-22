<?php

declare(strict_types=1);

namespace Munus\Tests;

use Munus\Collection\GenericList;
use Munus\Collection\Stream;
use Munus\Control\Option;
use Munus\Exception\UnsupportedOperationException;
use Munus\Tuple;
use PHPUnit\Framework\TestCase;

final class TupleTest extends TestCase
{
    public function testTupleArity(): void
    {
        self::assertEquals(1, Tuple::of('a')->arity());
        self::assertEquals(5, Tuple::of('M', 'u', 'n', 'u', 's')->arity());
    }

    public function testTupleSizeFail(): void
    {
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Invalid number of elements');
        Tuple::of(1, 2, 3, 4, 5, 6, 7, 8, 9);
    }

    public function testTupleArrayAccess(): void
    {
        $tuple = Tuple::of('Munus', 'is', 'awesome');

        self::assertTrue(isset($tuple[0]));
        self::assertTrue(isset($tuple[1]));
        self::assertTrue(isset($tuple[2]));
        self::assertFalse(isset($tuple[3]));

        self::assertEquals('Munus', $tuple[0]);
        self::assertEquals('is', $tuple[1]);
        self::assertEquals('awesome', $tuple[2]);

        $this->expectException(\RuntimeException::class);
        $not = $tuple[3];
    }

    public function testTupleToArray(): void
    {
        self::assertEquals([1, 2], Tuple::of(1, 2)->toArray());
        self::assertEquals(['a', 'b', 'c'], Tuple::of('a', 'b')->concat(Tuple::of('c'))->toArray());
    }

    public function testTupleConcat(): void
    {
        $tuple = Tuple::of(1, 2);
        $newTuple = $tuple->concat(Tuple::of(3, 4));
        self::assertNotSame($tuple, $newTuple);
        self::assertInstanceOf(Tuple\Tuple2::class, $tuple);
        self::assertInstanceOf(Tuple\Tuple4::class, $newTuple);
        self::assertEquals([1, 2, 3, 4], $newTuple->toArray());
    }

    public function testTupleConcatFail(): void
    {
        $tuple = Tuple::of(1, 2, 3, 4, 5, 6, 7, 8);
        self::assertInstanceOf(Tuple\Tuple8::class, $tuple);
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Invalid number of elements');
        $tuple->concat(Tuple::of(1));
    }

    public function testTupleAppend(): void
    {
        self::assertEquals([1, 2, 3], Tuple::of(1, 2)->append(3)->toArray());
        self::assertEquals(str_split('Munus'), Tuple::of('M', 'u')->append('n')->append('u')->append('s')->toArray());
    }

    public function testTuplePrepend(): void
    {
        self::assertEquals([3, 1, 2], Tuple::of(1, 2)->prepend(3)->toArray());
        self::assertEquals(str_split('Munus'), Tuple::of('u', 's')->prepend('n')->prepend('u')->prepend('M')->toArray());
    }

    public function testAppendToTuple8(): void
    {
        self::expectException(UnsupportedOperationException::class);

        Tuple::of(1, 2, 3, 4, 5, 6, 7, 8)->append(9);
    }

    public function testPrependToTuple8(): void
    {
        self::expectException(UnsupportedOperationException::class);

        Tuple::of(1, 2, 3, 4, 5, 6, 7, 8)->prepend(0);
    }

    public function testTupleApply(): void
    {
        self::assertEquals(6, Tuple::of(1, 2, 3)->apply(function (int $a, int $b, int $c): int {return $a + $b + $c; }));
        self::assertEquals('Munus', Tuple::of('M', 'u', 'n', 'u', 's')->apply(function () {return join('', func_get_args()); }));
    }

    public function testTupleEquals(): void
    {
        self::assertTrue(Tuple::of('A', 'b')->equals(Tuple::of('A', 'b')));
        self::assertFalse(Tuple::of('a', 'b')->equals(Tuple::of('A', 'b')));
        self::assertTrue(Tuple::of('A', 1)->equals(Tuple::of('A', 1)));
        self::assertFalse(Tuple::of('A', 1)->equals(Tuple::of('A', '1')));
        self::assertFalse(Tuple::of(1, 2)->equals(Tuple::of(1, 2, 3)));

        self::assertTrue(Tuple::of(Option::of('A'))->equals(Tuple::of(Option::of('A'))));
        self::assertFalse(Tuple::of(Option::of('A'))->equals(Tuple::of(Option::of('a'))));
        self::assertTrue(Tuple::of(GenericList::of(1, 2, 3))->equals(Tuple::of(Stream::of(1, 2, 3))));
    }

    public function testTupleMaximumValues(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        Tuple::of('This', 'is', 'a', 'really', 'bad', 'idea', 'above', 'eight', 'values');
    }

    public function testTupleSetOffset(): void
    {
        $this->expectException(UnsupportedOperationException::class);
        Tuple::of('a')[0] = 'bad idea';
    }

    public function testTupleUnsetOffset(): void
    {
        $this->expectException(UnsupportedOperationException::class);
        unset(Tuple::of('a')[0]);
    }

    public function testTupleMap(): void
    {
        self::assertTrue(Tuple::of(3.0, 4.0)->equals(Tuple::of(9, 16)->map('sqrt')));
        self::assertTrue(Tuple::of('A', 'B', 'C')->equals(Tuple::of('a', 'b', 'c')->map('strtoupper')));
    }
}
