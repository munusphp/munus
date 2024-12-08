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
    /**
     * @dataProvider arityTestData
     *
     * @param array<string> $values
     */
    public function testTupleArity(array $values, int $arity): void
    {
        self::assertEquals($arity, Tuple::of(...$values)->arity());
    }

    /**
     * @return array<array<string[]|int>>
     */
    public static function arityTestData(): array
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
     *
     * @param array<string> $values
     */
    public function testTupleToArray(array $values): void
    {
        self::assertEquals($values, Tuple::of(...$values)->toArray());
    }

    /**
     * @return array<array<array<string>>>
     */
    public static function toArrayTestData(): array
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
     *
     * @param array<string> $firstValues
     * @param array<string> $secondValues
     * @param array<string> $result
     */
    public function testTupleConcat(array $firstValues, array $secondValues, string $method, array $result): void
    {
        $tuple = Tuple::of(...$firstValues);
        $secondTuple = Tuple::of(...$secondValues);
        $newTuple = call_user_func([$tuple, $method], $secondTuple);

        self::assertNotSame($tuple, $newTuple);
        self::assertEquals($result, $newTuple->toArray());
    }

    /**
     * @return array<array<array<string>|string>>
     */
    public static function concatTestData(): array
    {
        return [
            [[], [], 'concatTuple0', []],
            [[], ['a'], 'concatTuple1', ['a']],
            [[], ['a', 'b'], 'concatTuple2', ['a', 'b']],
            [[], ['a', 'b', 'c'], 'concatTuple3', ['a', 'b', 'c']],
            [[], ['a', 'b', 'c', 'd'], 'concatTuple4', ['a', 'b', 'c', 'd']],
            [[], ['a', 'b', 'c', 'd', 'e'], 'concatTuple5', ['a', 'b', 'c', 'd', 'e']],
            [[], ['a', 'b', 'c', 'd', 'e', 'f'], 'concatTuple6', ['a', 'b', 'c', 'd', 'e', 'f']],
            [[], ['a', 'b', 'c', 'd', 'e', 'f', 'g'], 'concatTuple7', ['a', 'b', 'c', 'd', 'e', 'f', 'g']],
            [[], ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h'], 'concatTuple8', ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h']],
            [['1'], [], 'concatTuple0', ['1']],
            [['1'], ['a'], 'concatTuple1', ['1', 'a']],
            [['1'], ['a', 'b'], 'concatTuple2', ['1', 'a', 'b']],
            [['1'], ['a', 'b', 'c'], 'concatTuple3', ['1', 'a', 'b', 'c']],
            [['1'], ['a', 'b', 'c', 'd'], 'concatTuple4', ['1', 'a', 'b', 'c', 'd']],
            [['1'], ['a', 'b', 'c', 'd', 'e'], 'concatTuple5', ['1', 'a', 'b', 'c', 'd', 'e']],
            [['1'], ['a', 'b', 'c', 'd', 'e', 'f'], 'concatTuple6', ['1', 'a', 'b', 'c', 'd', 'e', 'f']],
            [['1'], ['a', 'b', 'c', 'd', 'e', 'f', 'g'], 'concatTuple7', ['1', 'a', 'b', 'c', 'd', 'e', 'f', 'g']],
            [['1', '2'], [], 'concatTuple0', ['1', '2']],
            [['1', '2'], ['a'], 'concatTuple1', ['1', '2', 'a']],
            [['1', '2'], ['a', 'b'], 'concatTuple2', ['1', '2', 'a', 'b']],
            [['1', '2'], ['a', 'b', 'c'], 'concatTuple3', ['1', '2', 'a', 'b', 'c']],
            [['1', '2'], ['a', 'b', 'c', 'd'], 'concatTuple4', ['1', '2', 'a', 'b', 'c', 'd']],
            [['1', '2'], ['a', 'b', 'c', 'd', 'e'], 'concatTuple5', ['1', '2', 'a', 'b', 'c', 'd', 'e']],
            [['1', '2'], ['a', 'b', 'c', 'd', 'e', 'f'], 'concatTuple6', ['1', '2', 'a', 'b', 'c', 'd', 'e', 'f']],
            [['1', '2', '3'], [], 'concatTuple0', ['1', '2', '3']],
            [['1', '2', '3'], ['a'], 'concatTuple1', ['1', '2', '3', 'a']],
            [['1', '2', '3'], ['a', 'b'], 'concatTuple2', ['1', '2', '3', 'a', 'b']],
            [['1', '2', '3'], ['a', 'b', 'c'], 'concatTuple3', ['1', '2', '3', 'a', 'b', 'c']],
            [['1', '2', '3'], ['a', 'b', 'c', 'd'], 'concatTuple4', ['1', '2', '3', 'a', 'b', 'c', 'd']],
            [['1', '2', '3'], ['a', 'b', 'c', 'd', 'e'], 'concatTuple5', ['1', '2', '3', 'a', 'b', 'c', 'd', 'e']],
            [['1', '2', '3', '4'], [], 'concatTuple0', ['1', '2', '3', '4']],
            [['1', '2', '3', '4'], ['a'], 'concatTuple1', ['1', '2', '3', '4', 'a']],
            [['1', '2', '3', '4'], ['a', 'b'], 'concatTuple2', ['1', '2', '3', '4', 'a', 'b']],
            [['1', '2', '3', '4'], ['a', 'b', 'c'], 'concatTuple3', ['1', '2', '3', '4', 'a', 'b', 'c']],
            [['1', '2', '3', '4'], ['a', 'b', 'c', 'd'], 'concatTuple4', ['1', '2', '3', '4', 'a', 'b', 'c', 'd']],
            [['1', '2', '3', '4', '5'], [], 'concatTuple0', ['1', '2', '3', '4', '5']],
            [['1', '2', '3', '4', '5'], ['a'], 'concatTuple1', ['1', '2', '3', '4', '5', 'a']],
            [['1', '2', '3', '4', '5'], ['a', 'b'], 'concatTuple2', ['1', '2', '3', '4', '5', 'a', 'b']],
            [['1', '2', '3', '4', '5'], ['a', 'b', 'c'], 'concatTuple3', ['1', '2', '3', '4', '5', 'a', 'b', 'c']],
            [['1', '2', '3', '4', '5', '6'], [], 'concatTuple0', ['1', '2', '3', '4', '5', '6']],
            [['1', '2', '3', '4', '5', '6'], ['a'], 'concatTuple1', ['1', '2', '3', '4', '5', '6', 'a']],
            [['1', '2', '3', '4', '5', '6'], ['a', 'b'], 'concatTuple2', ['1', '2', '3', '4', '5', '6', 'a', 'b']],
            [['1', '2', '3', '4', '5', '6', '7'], [], 'concatTuple0', ['1', '2', '3', '4', '5', '6', '7']],
            [['1', '2', '3', '4', '5', '6', '7'], ['a'], 'concatTuple1', ['1', '2', '3', '4', '5', '6', '7', 'a']],
            [['1', '2', '3', '4', '5', '6', '7', '8'], [], 'concatTuple0', ['1', '2', '3', '4', '5', '6', '7', '8']],
        ];
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
     *
     * @param array<string> $expected
     * @param array<string> $values
     */
    public function testTupleAppend(array $expected, array $values, string $appendValue): void
    {
        self::assertEquals($expected, Tuple::of(...$values)->append($appendValue)->toArray());
    }

    /**
     * @return array<array<array<string>|string>>
     */
    public static function appendTestData(): array
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
     *
     * @param array<string> $expected
     * @param array<string> $values
     */
    public function testTuplePrepend(array $expected, array $values, string $appendValue): void
    {
        self::assertEquals($expected, Tuple::of(...$values)->prepend($appendValue)->toArray());
    }

    /**
     * @return array<array<array<string>|string>>
     */
    public static function prependTestData(): array
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
