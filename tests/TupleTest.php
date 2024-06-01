<?php

declare(strict_types=1);

namespace Munus\Tests;

use Munus\Collection\GenericList;
use Munus\Collection\Stream;
use Munus\Control\Option;
use Munus\Exception\UnsupportedOperationException;
use Munus\Tests\Helpers\TupleConcatTestHelper;
use Munus\Tuple;
use PHPUnit\Framework\TestCase;

final class TupleTest extends TestCase
{
    /**
     * @dataProvider arityTestData
     */
    public function testTupleArity(array $values, int $arity): void
    {
        self::assertEquals($arity, Tuple::of(...$values)->arity());
    }

    public function arityTestData(): array
    {
        return [
            [['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'], 8],
            [['a', 'b', 'c', 'd', 'e', 'f', 'g'], 7],
            [['a', 'b', 'c', 'd', 'e', 'f'], 6],
            [['a', 'b', 'c', 'd', 'e'], 5],
            [['a', 'b', 'c', 'd'], 4],
            [['a', 'b', 'c'], 3],
            [['a', 'b'], 2],
            [['a'], 1],
            [[], 0],
        ];
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

    /**
     * @dataProvider toArrayTestData
     */
    public function testTupleToArray(array $values): void
    {
        self::assertEquals($values, Tuple::of(...$values)->toArray());
    }

    public function toArrayTestData(): array
    {
        return [
            [['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h']],
            [['a', 'b', 'c', 'd', 'e', 'f', 'g']],
            [['a', 'b', 'c', 'd', 'e', 'f']],
            [['a', 'b', 'c', 'd', 'e']],
            [['a', 'b', 'c', 'd']],
            [['a', 'b', 'c']],
            [['a', 'b']],
            [['a']],
            [[]],
        ];
    }

    /**
     * @dataProvider concatTestData
     */
    public function testTupleConcat(array $firstValues, array $secondValues, string $method, array $result): void
    {
        $tuple = Tuple::of(...$firstValues);
        $secondTuple = Tuple::of(...$secondValues);
        $newTuple = call_user_func([$tuple, $method], $secondTuple);

        self::assertNotSame($tuple, $newTuple);
        self::assertEquals($result, $newTuple->toArray());
    }

    public function concatTestData(): array
    {
        return TupleConcatTestHelper::concatTestData();
    }

    public function testTupleConcatFail(): void
    {
        $tuple = Tuple::of(1, 2, 3, 4, 5, 6, 7, 8);
        self::assertInstanceOf(Tuple\Tuple8::class, $tuple);
        self::expectException(\InvalidArgumentException::class);
        self::expectExceptionMessage('Invalid number of elements');
        $tuple->concat(Tuple::of(1));
    }

    /**
     * @dataProvider appendTestData
     */
    public function testTupleAppend(array $expected, array $values, string $appendValue): void
    {
        self::assertEquals($expected, Tuple::of(...$values)->append($appendValue)->toArray());
    }

    public function appendTestData(): array
    {
        return [
            [['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'], ['a', 'b', 'c', 'd', 'e', 'f', 'g'], 'h'],
            [['a', 'b', 'c', 'd', 'e', 'f', 'g'], ['a', 'b', 'c', 'd', 'e', 'f'], 'g'],
            [['a', 'b', 'c', 'd', 'e', 'f'], ['a', 'b', 'c', 'd', 'e'], 'f'],
            [['a', 'b', 'c', 'd', 'e'], ['a', 'b', 'c', 'd'], 'e'],
            [['a', 'b', 'c', 'd'], ['a', 'b', 'c'], 'd'],
            [['a', 'b', 'c'], ['a', 'b'], 'c'],
            [['a', 'b'], ['a'], 'b'],
            [['a'], [], 'a'],
        ];
    }

    /**
     * @dataProvider prependTestData
     */
    public function testTuplePrepend(array $expected, array $values, string $appendValue): void
    {
        self::assertEquals($expected, Tuple::of(...$values)->prepend($appendValue)->toArray());
    }

    public function prependTestData(): array
    {
        return [
            [['h', 'a', 'b', 'c', 'd', 'e', 'f', 'g'], ['a', 'b', 'c', 'd', 'e', 'f', 'g'], 'h'],
            [['g', 'a', 'b', 'c', 'd', 'e', 'f'], ['a', 'b', 'c', 'd', 'e', 'f'], 'g'],
            [['f', 'a', 'b', 'c', 'd', 'e'], ['a', 'b', 'c', 'd', 'e'], 'f'],
            [['e', 'a', 'b', 'c', 'd'], ['a', 'b', 'c', 'd'], 'e'],
            [['d', 'a', 'b', 'c'], ['a', 'b', 'c'], 'd'],
            [['c', 'a', 'b'], ['a', 'b'], 'c'],
            [['b', 'a'], ['a'], 'b'],
            [['a'], [], 'a'],
        ];
    }

    public function testTupleMultipleAppend(): void
    {
        self::assertEquals(str_split('Munus'), Tuple::of('M', 'u')->append('n')->append('u')->append('s')->toArray());
    }

    public function testTupleMultiplePrepend(): void
    {
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
        self::assertEquals(6, Tuple::of(1, 2, 3)->apply(function (int $a, int $b, int $c): int {return $a + $b + $c; })); // @phpstan-ignore-line
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
