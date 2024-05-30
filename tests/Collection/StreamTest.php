<?php

declare(strict_types=1);

namespace Munus\Tests\Collection;

use Munus\Collection\Stream;
use Munus\Collection\Stream\Collectors;
use Munus\Collection\Traversable;
use Munus\Control\Option;
use Munus\Exception\NoSuchElementException;
use Munus\Lazy;
use Munus\Tests\Stub\Event;
use PHPUnit\Framework\TestCase;

final class StreamTest extends TestCase
{
    public function testStreamIterator(): void
    {
        $iterator = Stream::ofAll([1, 2, 3])->iterator();

        self::assertTrue($iterator->hasNext());
        self::assertEquals(1, $iterator->current());
        self::assertEquals(1, $iterator->next());
        self::assertEquals(2, $iterator->next());
        self::assertEquals(3, $iterator->next());
        self::assertFalse($iterator->hasNext());
    }

    public function testStreamFind(): void
    {
        self::assertEquals(Option::of('munus'), Stream::ofAll(['lambda', 'munus', 'function'])->find(function (string $name) {
            return $name === 'munus';
        }));
        self::assertEquals(Option::of(null), Stream::ofAll(['lambda', 'missing', 'function'])->find(function (string $name) {
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

    public function testStreamFilter(): void
    {
        self::assertTrue(Stream::of(3, 6, 9)->equals(Stream::from(1)->filter(function (int $n): bool {
            return $n % 3 === 0;
        })->take(3)));
        self::assertTrue(Stream::of('u', 'u')->equals(Stream::ofAll(str_split('Munus'))->filter(function (string $char): bool {
            return $char === 'u';
        })));
    }

    public function testStreamFilterNot(): void
    {
        self::assertTrue(Stream::of(1, 2, 4)->equals(Stream::from(1)->filterNot(function (int $n): bool {
            return $n % 3 === 0;
        })->take(3)));
        self::assertTrue(Stream::of('M', 'n', 's')->equals(Stream::ofAll(str_split('Munus'))->filterNot(function (string $char): bool {
            return $char === 'u';
        })));
    }

    public function testStreamLength(): void
    {
        self::assertSame(5, Stream::from(0)->take(5)->length());
        self::assertSame(5, Stream::range(6, 10)->length());
        self::assertSame(5, Stream::ofAll(str_split('Munus'))->length());
        self::assertSame(0, Stream::empty()->length());
    }

    public function testStreamContains(): void
    {
        self::assertTrue(Stream::ofAll([1, 2, 3])->contains(2));
    }

    public function testStreamContainsAll(): void
    {
        self::assertTrue(Stream::ofAll([1, 2, 3])->containsAll(Stream::ofAll([3, 2, 1])));
        self::assertTrue(Stream::ofAll([1, 2, 3])->containsAll(Stream::ofAll([2, 1])));
        self::assertTrue(Stream::ofAll([1, 2, 3])->containsAll(Stream::ofAll([1])));

        self::assertFalse(Stream::ofAll([1, 2, 3])->containsAll(Stream::ofAll([1, 2, 3, 4])));
        self::assertFalse(Stream::ofAll([1, 2, 3])->containsAll(Stream::ofAll([1, 2, 4])));
        self::assertFalse(Stream::ofAll([1, 2, 3])->containsAll(Stream::ofAll([1, 4])));
        self::assertFalse(Stream::ofAll([1, 2, 3])->containsAll(Stream::ofAll([0])));
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
        self::assertTrue(Stream::range(1, 5)->equals(Stream::ofAll([1, 2, 3, 4, 5])));
    }

    public function testStreamFrom(): void
    {
        self::assertTrue(Stream::range(1, 5)->equals(Stream::from(1)->take(5)));
        self::assertTrue(Stream::range(3, 5)->equals(Stream::from(3)->take(3)));
        self::assertTrue(Stream::of(101, 102, 103)->equals(Stream::from(101)->take(3)));
    }

    public function testStreamContinually(): void
    {
        mt_srand(43);
        self::assertTrue(Stream::of(9, 9, 6)->equals(Stream::continually(function () {return mt_rand(1, 10); })->take(3)));
        self::assertTrue(Stream::of('m', 'm', 'm')->equals(Stream::continually(function () {return 'm'; })->take(3)));
    }

    public function testStreamIterate(): void
    {
        self::assertTrue(Stream::of(1, 2, 4)->equals(Stream::iterate(1, function (int $i) {return $i * 2; })->take(3)));
        self::assertTrue(Stream::of(0, -1, -2)->equals(Stream::iterate(0, function (int $i) {return --$i; })->take(3)));
        self::assertTrue(Stream::of('a', 'aa', 'aaaa')->equals(Stream::iterate('a', function (string $t) {return $t.$t; })->take(3)));
    }

    public function testStreamCons(): void
    {
        mt_srand(43);
        self::assertTrue(Stream::of(5, 9, 9, 6)->equals(Stream::cons(5, function () {return mt_rand(1, 10); })->take(4)));
        self::assertTrue(Stream::of('M', 'u')->equals(Stream::cons('M', function () {return 'u'; })->take(2)));
        $counter = 1;
        self::assertTrue(Stream::of(1, 2, 3)->equals(Stream::cons(1, /** @return int|Stream */ function () use (&$counter) {
            return ++$counter >= 4 ? Stream::empty() : $counter;
        })));
    }

    public function testStreamDropWhile(): void
    {
        $stream = Stream::range(1, 10);
        self::assertTrue(Stream::range(5, 10)->equals($stream->dropWhile(function (int $value): bool {
            return $value < 5;
        })));
        self::assertTrue(Stream::range(1, 10)->equals($stream->dropWhile(function (int $value): bool {
            return false;
        })));
        self::assertTrue(Stream::empty()->equals($stream->dropWhile(function (int $value): bool {
            return true;
        })));
    }

    public function testStreamDropUntil(): void
    {
        $stream = Stream::range(1, 10);
        self::assertTrue(Stream::range(5, 10)->equals($stream->dropUntil(function (int $value): bool {
            return $value === 5;
        })));
        self::assertTrue(Stream::empty()->equals($stream->dropUntil(function (int $value): bool {
            return false;
        })));
    }

    public function testStreamTake(): void
    {
        self::assertTrue(Stream::of(1, 2, 3)->equals(Stream::of(1, 2, 3)->take(3)));
        self::assertTrue(Stream::of(1, 2, 3)->equals(Stream::of(1, 2, 3)->take(4)));
        self::assertTrue(Stream::empty()->equals(Stream::empty()->take(3)));
        self::assertTrue(Stream::of(1, 2, 3)->equals(Stream::of(1, 2, 3, 4)->take(3)));
        self::assertTrue(Stream::range(1, 3)->equals(Stream::range(1, 10)->take(3)));
    }

    public function testStreamDrop(): void
    {
        self::assertTrue(Stream::empty()->equals(Stream::of(1, 2, 3)->drop(3)));
        self::assertTrue(Stream::of(1, 2, 3)->equals(Stream::of(1, 2, 3)->drop(0)));
        self::assertTrue(Stream::of(2, 3, 4)->equals(Stream::of(1, 2, 3, 4)->drop(1)));
        self::assertTrue(Stream::range(4, 10)->equals(Stream::range(1, 10)->drop(3)));
    }

    public function testStreamPeek(): void
    {
        $values = [];
        Stream::of(1, 2, 3)->peek(function ($value) use (&$values) {
            $values[] = $value;
        })->collect(Collectors::toList());

        self::assertEquals([1, 2, 3], $values);
    }

    public function testEmptyStreamPeek(): void
    {
        $stream = Stream::empty();
        self::assertSame($stream, $stream->peek(function () {throw new \RuntimeException('this will not happen'); }));
    }

    public function testStreamToOption(): void
    {
        self::assertTrue(Option::of(1)->equals(Stream::of(1, 2, 3)->toOption()));
        self::assertTrue(Option::none()->equals(Stream::empty()->toOption()));
    }

    public function testStreamToStream(): void
    {
        // is there logical use case for this?
        self::assertTrue(Stream::of(1, 2, 3)->equals(Stream::of(1, 2, 3)->toStream()));
    }

    public function testStreamPrepend(): void
    {
        self::assertTrue(Stream::of(1, 2, 3)->equals(Stream::of(2, 3)->prepend(1)));
        self::assertTrue(Stream::of(1)->equals(Stream::empty()->prepend(1)));
    }

    public function testStreamAppend(): void
    {
        self::assertTrue(Stream::of(1, 2, 3)->equals(Stream::of(1, 2)->append(3)));
        self::assertTrue(Stream::of(1)->equals(Stream::empty()->append(1)));
    }

    public function testStreamAppendAllOnEmpty(): void
    {
        self::assertTrue(Stream::of(1, 2, 3)->equals(Stream::empty()->appendAll(Stream::of(1, 2, 3))));

        $empty = Stream::empty();
        self::assertSame($empty, $empty->appendAll(Stream::empty()));
    }

    public function testStreamAppendAll(): void
    {
        self::assertTrue(Stream::of(1, 2, 3, 4)->equals(Stream::of(1, 2)->appendAll(Stream::of(3, 4))));
        self::assertTrue(Stream::of('a', 'b', 'c', 'd', 'e')->equals(Stream::of('a')->appendAll(Stream::of('b', 'c', 'd', 'e'))));
    }

    public function testStreamPrependAll(): void
    {
        self::assertTrue(Stream::of(1, 2, 3)->equals(Stream::empty()->prependAll(Stream::of(1, 2, 3))));
        self::assertTrue(Stream::of(1, 2, 3, 4)->equals(Stream::of(3, 4)->prependAll(Stream::of(1, 2))));
        self::assertTrue(Stream::of('a', 'b', 'c', 'd', 'e')->equals(Stream::of('e')->prependAll(Stream::of('a', 'b', 'c', 'd'))));
    }

    public function testStreamPrependAllEmptyStream(): void
    {
        $stream = Stream::of(1, 2, 3);

        self::assertSame($stream, $stream->prependAll(Stream::empty()));
    }

    public function testStreamToArray(): void
    {
        self::assertEquals([1, 2, 3, 4, 5], Stream::range(1, 5)->toArray());
    }

    public function testStreamSorted(): void
    {
        self::assertTrue(Stream::of('a', 'b', 'c', 'd', 'e')->equals(Stream::of('e', 'd', 'c', 'b', 'a')->sorted()));
    }

    public function testFlatMap(): void
    {
        self::assertTrue(Stream::ofAll([1, 2, 3])->flatMap(fn ($value) => Stream::of($value, $value))->equals(
            Stream::ofAll([1, 1, 2, 2, 3, 3])
        ));

        self::assertTrue(Stream::ofAll([Stream::of(1, 1), Stream::of(2, 2), Stream::of(3, 3)])->flatMap(fn (Traversable $value) => $value->take(1))->equals(
            Stream::ofAll([1, 2, 3])
        ));
    }

    public function testIndexOf(): void
    {
        $stream = Stream::of('a', 'b', 'c', 'd', 'e', 'f');

        self::assertSame(0, $stream->indexOf('a'));
        self::assertSame(1, $stream->indexOf('b'));
        self::assertSame(5, $stream->indexOf('f'));
        self::assertSame(-1, $stream->indexOf('g'));
    }

    public function testIndexOfIsUsingComparator(): void
    {
        $stream = Stream::of(new Event('1', 'payment.failed'), new Event('2', 'payment.pending'));

        self::assertSame(1, $stream->indexOf(new Event('3', 'payment.pending')));
        self::assertSame(-1, $stream->indexOf(new Event('1', 'payment.success')));
    }

    public function testIndexOfOnEmptyStream(): void
    {
        self::assertSame(-1, Stream::empty()->indexOf('a'));
    }

    public function testHeadOfEmptyStream(): void
    {
        $this->expectException(NoSuchElementException::class);

        Stream::empty()->head();
    }

    public function testTailOfEmptyStream(): void
    {
        $this->expectException(NoSuchElementException::class);

        Stream::empty()->tail();
    }
}
