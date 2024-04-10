<?php

declare(strict_types=1);

namespace Munus\Tests\Collection;

use Munus\Collection\GenericList;
use Munus\Collection\Set;
use Munus\Collection\Stream;
use Munus\Collection\Stream\Collectors;
use Munus\Collection\Traversable;
use Munus\Control\Option;
use PHPUnit\Framework\TestCase;

final class GenericListTest extends TestCase
{
    public function testListRange(): void
    {
        self::assertTrue(GenericList::of(1, 2, 3)->equals(GenericList::range(1, 3)));
        self::assertTrue(GenericList::of(-3, -2, -1)->equals(GenericList::range(-3, -1)));
        self::assertEquals(10, GenericList::range(1, 10)->length());
    }

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

    public function testListPeek(): void
    {
        $head = null;
        $list = GenericList::of(42, 2, 3);
        self::assertSame($list, $list->peek(function ($value) use (&$head) {$head = $value; }));
        self::assertEquals($head, 42);
    }

    public function testEmptyListPeek(): void
    {
        $list = GenericList::empty();
        self::assertSame($list, $list->peek(function () {throw new \RuntimeException('this will not happen'); }));
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

    public function testListReduce(): void
    {
        self::assertEquals('abcd', GenericList::of('a', 'b', 'c', 'd')->reduce(function (string $a, string $b): string {return $a.$b; }));
    }

    public function testListForEach(): void
    {
        $counter = 0;
        GenericList::of(1, 2, 3)->forEach(function (int $x) use (&$counter) {
            self::assertEquals(++$counter, $x);
        });
        self::assertEquals(3, $counter);
    }

    public function testListFold(): void
    {
        self::assertEquals(6, GenericList::of('a', 'bbb', 'cc')->fold(0, function (int $a, string $b): int {return $a + mb_strlen($b); }));
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
            GenericList::of('a', 'b', 'c')->equals(GenericList::of('a', 'b')->append('c'))
        );
    }

    public function testListDropWhile(): void
    {
        $list = GenericList::range(1, 10);
        self::assertTrue(GenericList::range(5, 10)->equals($list->dropWhile(function (int $value): bool {
            return $value < 5;
        })));
        self::assertTrue(GenericList::range(1, 10)->equals($list->dropWhile(function (int $value): bool {
            return false;
        })));
        self::assertTrue(GenericList::empty()->equals($list->dropWhile(function (int $value): bool {
            return true;
        })));
    }

    public function testListDropUntil(): void
    {
        $list = GenericList::range(1, 10);
        self::assertTrue(GenericList::range(5, 10)->equals($list->dropUntil(function (int $value): bool {
            return $value === 5;
        })));
        self::assertTrue(GenericList::empty()->equals($list->dropUntil(function (int $value): bool {
            return false;
        })));
    }

    public function testListTake(): void
    {
        $list = GenericList::of(1, 2, 3);
        self::assertSame($list, $list->take(3));
        self::assertSame($list, $list->take(4));
        self::assertTrue(GenericList::empty()->equals(GenericList::empty()->take(3)));
        self::assertTrue(GenericList::of(1, 2, 3)->equals(GenericList::of(1, 2, 3, 4)->take(3)));
    }

    public function testListDrop(): void
    {
        $list = GenericList::of(1, 2, 3);
        self::assertSame($list, $list->drop(0));
        self::assertSame($list, $list->drop(-1));
        self::assertTrue(GenericList::empty()->equals(GenericList::empty()->drop(0)));
        self::assertTrue(GenericList::of(4)->equals(GenericList::of(1, 2, 3, 4)->drop(3)));
        self::assertTrue(GenericList::of(2, 3, 4)->equals(GenericList::of(1, 2, 3, 4)->drop(1)));
    }

    public function testListFilter(): void
    {
        self::assertTrue(GenericList::of(3, 6, 9)->equals(GenericList::ofAll(range(1, 30))->filter(function (int $n): bool {
            return $n % 3 === 0;
        })->take(3)));
    }

    public function testListFilterNot(): void
    {
        self::assertTrue(GenericList::of(1, 2, 4)->equals(GenericList::ofAll(range(1, 30))->filterNot(function (int $n): bool {
            return $n % 3 === 0;
        })->take(3)));
    }

    public function testListCollect(): void
    {
        self::assertTrue(Set::of('a', 'b', 'c')->equals(
            GenericList::of('a', 'b', 'c')->collect(Collectors::toSet())
        ));
    }

    public function testListToOption(): void
    {
        self::assertTrue(Option::of('php')->equals(GenericList::of('php', 'is', 'awesome')->toOption()));
        self::assertTrue(Option::none()->equals(GenericList::empty()->toOption()));
    }

    public function testListToStream(): void
    {
        self::assertTrue(
            GenericList::ofAll([1, 2, 3])->toStream()->equals(Stream::ofAll([1, 2, 3]))
        );
        self::assertTrue(Stream::empty()->equals(GenericList::empty()));
    }

    public function testToArray(): void
    {
        self::assertEquals([1, 2, 3], GenericList::of(1, 2, 3)->toArray());
    }

    public function testListPrependAll(): void
    {
        self::assertTrue(GenericList::of(1, 2, 3)->equals(GenericList::empty()->prependAll(GenericList::of(1, 2, 3))));
        self::assertTrue(GenericList::of(1, 2, 3, 4)->equals(GenericList::of(3, 4)->prependAll(GenericList::of(1, 2))));
        self::assertTrue(GenericList::of('a', 'b', 'c', 'd', 'e')->equals(GenericList::of('e')->prependAll(GenericList::of('a', 'b', 'c', 'd'))));
    }

    public function testListAppendAllOnEmpty(): void
    {
        self::assertTrue(GenericList::of(1, 2, 3)->equals(GenericList::empty()->appendAll(GenericList::of(1, 2, 3))));

        $empty = GenericList::empty();
        self::assertSame($empty, $empty->appendAll(GenericList::empty()));
    }

    public function testListAppendAll(): void
    {
        self::assertTrue(GenericList::of(1, 2, 3, 4)->equals(GenericList::of(1, 2)->appendAll(GenericList::of(3, 4))));
        self::assertTrue(GenericList::of('a', 'b', 'c', 'd', 'e')->equals(GenericList::of('a')->appendAll(GenericList::of('b', 'c', 'd', 'e'))));
    }

    public function testListSorted(): void
    {
        self::assertTrue(GenericList::of('a', 'b', 'c', 'd', 'e')->equals(GenericList::of('e', 'd', 'c', 'b', 'a')->sorted()));
    }

    public function testListContainsAll(): void
    {
        self::assertTrue(GenericList::ofAll([1, 2, 3])->containsAll(GenericList::ofAll([3, 2, 1])));
        self::assertTrue(GenericList::ofAll([1, 2, 3])->containsAll(GenericList::ofAll([2, 1])));
        self::assertTrue(GenericList::ofAll([1, 2, 3])->containsAll(GenericList::ofAll([1])));

        self::assertFalse(GenericList::ofAll([1, 2, 3])->containsAll(GenericList::ofAll([1, 2, 3, 4])));
        self::assertFalse(GenericList::ofAll([1, 2, 3])->containsAll(GenericList::ofAll([1, 2, 4])));
        self::assertFalse(GenericList::ofAll([1, 2, 3])->containsAll(GenericList::ofAll([1, 4])));
        self::assertFalse(GenericList::ofAll([1, 2, 3])->containsAll(GenericList::ofAll(['a'])));
    }

    public function testFlatMap(): void
    {
        self::assertTrue(GenericList::ofAll([1, 2, 3])->flatMap(fn ($value) => GenericList::of($value, $value))->equals(
            GenericList::ofAll([1, 1, 2, 2, 3, 3])
        ));

        self::assertTrue(GenericList::ofAll([GenericList::of(1, 1), GenericList::of(2, 2), GenericList::of(3, 3)])->flatMap(fn (Traversable $value) => $value->take(1))->equals(
            GenericList::ofAll([1, 2, 3])
        ));
    }
}
