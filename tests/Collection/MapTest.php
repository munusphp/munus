<?php

declare(strict_types=1);

namespace Munus\Tests\Collection;

use Munus\Collection\GenericList;
use Munus\Collection\Map;
use Munus\Collection\Set;
use Munus\Collection\Stream;
use Munus\Collection\Stream\Collectors;
use Munus\Control\Option;
use Munus\Exception\NoSuchElementException;
use Munus\Exception\UnsupportedOperationException;
use Munus\Tests\Stub\Event;
use Munus\Tuple;
use PHPUnit\Framework\TestCase;

final class MapTest extends TestCase
{
    public function testMapPutAndGet(): void
    {
        $map = Map::empty();
        $map = $map->put('munus', 'is awesome');

        self::assertEquals(Option::of('is awesome'), $map->get('munus'));
        self::assertEquals(Option::none(), $map->get('wrong-key'));
    }

    public function testMapRemove(): void
    {
        $map = Map::fromArray(['some' => 'value']);

        self::assertEquals(Option::none(), $map->remove('some')->get('some'));
        self::assertSame($map, $map->remove('not-existing-key'));
        self::assertNotSame($map, $map->remove('some'));
    }

    public function testMapGetWithoutArgument(): void
    {
        self::assertTrue(Option::of('b')->equals(Map::fromArray(['a' => 'b'])->get()));
        self::assertTrue(Option::none()->equals(Map::empty()->get()));
    }

    public function testMapImmutability(): void
    {
        $map = Map::fromArray(['munus' => 'is awesome']);
        $newMap = $map->put('munus2', 'is better');

        self::assertNotSame($map, $newMap);
        self::assertEquals(Option::none(), $map->get('munus2'));
    }

    public function testMapLength(): void
    {
        $map = Map::fromArray(['munus' => 'is awesome']);
        self::assertEquals(1, $map->length());
        self::assertEquals(2, $map->put('php', 'is awesome')->length());
    }

    public function testMapHead(): void
    {
        self::assertTrue(Map::fromArray(['a' => 'b', 'c' => 'd', 'e' => 'f'])->head()->equals(Tuple::of('a', 'b')));
        self::assertTrue(Map::fromArray(['e' => 'f', 'a' => 'b'])->head()->equals(Tuple::of('e', 'f')));

        $this->expectException(NoSuchElementException::class);
        Map::empty()->head();
    }

    public function testMapTail(): void
    {
        self::assertTrue(Map::fromArray(['a' => 'b', 'c' => 'd', 'e' => 'f'])->tail()->equals(Map::fromArray(['c' => 'd', 'e' => 'f'])));
        self::assertTrue(Map::fromArray(['e' => 'f', 'a' => 'b'])->tail()->equals(Map::fromArray(['a' => 'b'])));

        $this->expectException(NoSuchElementException::class);
        Map::empty()->tail();
    }

    public function testIsEmpty(): void
    {
        self::assertTrue(Map::empty()->isEmpty());
        self::assertFalse(Map::empty()->put('some', 'value')->isEmpty());
        self::assertTrue(Map::fromArray(['some' => 'value'])->remove('some')->isEmpty());
    }

    public function testEmptyMapPeek(): void
    {
        $map = Map::empty();
        self::assertSame($map, $map->peek(function () {throw new \RuntimeException('this will not happen'); }));
    }

    public function testMapPeek(): void
    {
        $counter = 0;
        Map::fromArray(['a' => 1])->peek(function (int $head) use (&$counter) {$counter = $head; });
        self::assertEquals(1, $counter);
    }

    public function testMapTake(): void
    {
        $map = Map::fromArray(['a' => 'apple', 'b' => 'banana', 'a42' => 'pear', 'd' => 'orange']);

        self::assertTrue(Map::fromArray(['a' => 'apple', 'b' => 'banana'])->equals($map->take(2)));
        self::assertTrue(Map::fromArray(['a' => 'apple'])->equals($map->take(1)));
        self::assertTrue($map->equals($map->take(4)));
        self::assertSame($map, $map->take(5));
        self::assertNotSame($map, $map->take(3));
    }

    public function testMapDrop(): void
    {
        $map = Map::fromArray(['a' => 'apple', 'b' => 'banana', 'a42' => 'pear', 'd' => 'orange']);

        self::assertTrue(Map::fromArray(['a42' => 'pear', 'd' => 'orange'])->equals($map->drop(2)));
        self::assertTrue(Map::fromArray(['d' => 'orange'])->equals($map->drop(3)));
        self::assertTrue($map->equals($map->drop(0)));
        self::assertSame($map, $map->drop(-1));
        self::assertTrue(Map::empty()->equals($map->drop(4)));
    }

    public function testMapFilter(): void
    {
        $map = Map::fromArray(['a' => 'apple', 'b' => 'banana', 'a42' => 'pear', 'd' => 'orange']);

        self::assertTrue(Map::fromArray(['a' => 'apple'])->equals(
            $map->filter(function ($entry) {return $entry[1] === 'apple'; })
        ));

        self::assertTrue(Map::fromArray(['a42' => 'pear'])->equals(
            $map->filter(function ($entry) {return $entry[0] === 'a42'; })
        ));

        self::assertNotSame($map, $map->filter(function () {return true; }));
    }

    public function testMapMap(): void
    {
        $map = Map::fromArray(['a' => 'b', 'c' => 'd']);

        self::assertTrue(Map::fromArray(['A' => 'B', 'C' => 'D'])->equals(
            $map->map(function ($entry) {return $entry->map('strtoupper'); })
        ));
        self::assertTrue(Map::fromArray(['aa' => 'bb', 'cc' => 'dd'])->equals(
            $map->map(function ($entry) {return $entry->map(function (string $v): string {return $v.$v; }); })
        ));

        self::assertNotSame($map, $map->map(function ($entry) {return $entry; }));
    }

    public function testMapMapKeys(): void
    {
        $map = Map::fromArray(['a' => 'b', 'c' => 'd']);

        self::assertTrue(Map::fromArray(['A' => 'b', 'C' => 'd'])->equals(
            $map->mapKeys('strtoupper')
        ));

        self::assertNotSame($map, $map->mapKeys('ucfirst'));
    }

    public function testMapMapValues(): void
    {
        $map = Map::fromArray(['a' => 'b', 'c' => 'd']);

        self::assertTrue(Map::fromArray(['a' => 'B', 'c' => 'D'])->equals(
            $map->mapValues('strtoupper')
        ));

        self::assertNotSame($map, $map->mapValues('ucfirst'));
    }

    public function testMapContains(): void
    {
        $map = Map::fromArray(['a' => 'b', 'c' => 'd']);

        self::assertTrue($map->contains(Tuple::of('a', 'b')));
        self::assertFalse($map->contains(Tuple::of('a', 'c')));
    }

    public function testMapContainsKey(): void
    {
        $map = Map::fromArray(['a' => 'b', 'c' => 'd']);

        self::assertTrue($map->containsKey('a'));
        self::assertFalse($map->containsKey('b'));
    }

    public function testMapContainsValue(): void
    {
        $map = Map::fromArray(['a' => 'b', 'c' => 'd', 'e' => Option::of('munus')]);

        self::assertTrue($map->containsValue('d'));
        self::assertFalse($map->containsValue('a')); // @phpstan-ignore-line phpstan is too smart and knows what exactly is in map
        self::assertTrue($map->containsValue('munus')); // @phpstan-ignore-line phpstan is too smart and knows what exactly is in map
    }

    public function testMapValues(): void
    {
        self::assertTrue(Stream::of('b', 'd')->equals(Map::fromArray(['a' => 'b', 'c' => 'd'])->values()));
        self::assertTrue(Stream::empty()->equals(Map::fromArray([])->values()));
    }

    public function testMapKeys(): void
    {
        self::assertTrue(Set::ofAll(['a', 'b', 'c'])->equals(Map::fromArray(['a' => '1', 'b' => '2', 'c' => '3'])->keys()));
    }

    public function testMapDropWhile(): void
    {
        $map = Map::fromArray(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4]);
        self::assertTrue(Map::fromArray(['c' => 3, 'd' => 4])->equals($map->dropWhile(function (Tuple $node): bool {
            return $node[1] < 3;
        })));
        self::assertTrue(Map::fromArray(['d' => 4])->equals($map->dropWhile(function (Tuple $node): bool {
            return $node[0] !== 'd';
        })));
        self::assertTrue(Map::fromArray(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4])->equals($map->dropWhile(function (Tuple $node): bool {
            return false;
        })));
        self::assertTrue(Map::empty()->equals($map->dropWhile(function (Tuple $node): bool {
            return true;
        })));
    }

    public function testMapDropUntil(): void
    {
        $map = Map::fromArray(['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4]);
        self::assertTrue(Map::fromArray(['b' => 2, 'c' => 3, 'd' => 4])->equals($map->dropUntil(function (Tuple $node): bool {
            return $node[1] === 2;
        })));
        self::assertTrue(Map::fromArray(['c' => 3, 'd' => 4])->equals($map->dropUntil(function (Tuple $node): bool {
            return $node[0] === 'c';
        })));
        self::assertTrue(Map::empty()->equals($map->dropUntil(fn () => false)));
    }

    public function testMapMerge(): void
    {
        $merged = Map::fromArray(['a' => 'b', 'c' => 'd']);

        self::assertTrue($merged->equals(Map::fromArray(['a' => 'b'])->merge(Map::fromArray(['c' => 'd']))));
        self::assertSame($merged, $merged->merge(Map::empty()));
        self::assertSame($merged, Map::empty()->merge($merged));
    }

    public function testMapMergeAndDropKeyOnConflict(): void
    {
        $merged = Map::fromArray(['a' => 'b', 'c' => 'd']);

        self::assertTrue($merged->equals(Map::fromArray(['a' => 'b'])->merge(
            Map::fromArray(['a' => 'conflict', 'c' => 'd'])
        )));
    }

    public function testMapCollect(): void
    {
        self::assertTrue(GenericList::of(Tuple::of('a', 'b'), Tuple::of('c', 'd'))->equals(
            Map::fromArray(['a' => 'b', 'c' => 'd'])->collect(Collectors::toList())
        ));
        self::assertTrue(GenericList::empty()->equals(Map::empty()->collect(Collectors::toList())));
    }

    public function testMapToOption(): void
    {
        self::assertTrue(Option::none()->equals(Map::empty()->toOption()));
        self::assertTrue(Option::of('b')->equals(Map::fromArray(['a' => 'b', 'c' => 'd'])->toOption()));
    }

    public function testMapToStream(): void
    {
        self::assertTrue(Stream::of(Tuple::of('a', 'b'), Tuple::of('c', 'd'))->equals(
            Map::fromArray(['a' => 'b', 'c' => 'd'])->toStream()
        ));
        self::assertTrue(Stream::empty()->equals(Map::empty()->toStream()));
    }

    public function testMapToArray(): void
    {
        self::assertEquals([Tuple::of('a', 'b'), Tuple::of('c', 'd')], Map::fromArray(['a' => 'b', 'c' => 'd'])->toArray());
    }

    public function testMapSorted(): void
    {
        self::assertTrue(Map::fromArray(['a' => 'b', 'c' => 'd', 'e' => 'f'])->equals(Map::fromArray(['e' => 'f', 'c' => 'd', 'a' => 'b'])->sorted()));
    }

    public function testFlatMap(): void
    {
        self::assertTrue(Map::fromArray(['a' => 'b', 'c' => 'd'])->flatMap(fn ($tuple) => GenericList::of($tuple, Tuple::of($tuple[1], $tuple[0])))->equals( // @phpstan-ignore argument.type
            Map::fromArray(['a' => 'b', 'b' => 'a', 'c' => 'd', 'd' => 'c'])
        ));
    }

    public function testMapArrayAccessOffsetExists(): void
    {
        $map = Map::fromArray(['a' => 'b', 'c' => 'd']);

        self::assertTrue(isset($map['a']));
        self::assertTrue(isset($map['c']));
        self::assertFalse(isset($map['b']));
    }

    public function testMapArrayAccessOffsetGet(): void
    {
        $map = Map::fromArray(['a' => 'b', 'c' => 'd']);

        self::assertSame('b', $map['a']);
        self::assertSame('d', $map['c']);

        $this->expectException(NoSuchElementException::class);

        $a = $map['x'];
    }

    public function testMapArrayAccessOffsetSet(): void
    {
        $map = Map::fromArray(['a' => 'b', 'c' => 'd']);

        $this->expectException(UnsupportedOperationException::class);

        $map['e'] = 'f';
    }

    public function testMapArrayAccessOffsetUnset(): void
    {
        $map = Map::fromArray(['a' => 'b', 'c' => 'd']);

        $this->expectException(UnsupportedOperationException::class);

        unset($map['a']);
    }

    public function testMapWithObjects(): void
    {
        $event1 = new Event('1', 'same');
        $event2 = new Event('2', 'same');
        $event3 = new Event('2', 'different');

        $map = Map::empty();
        $map = $map->put($event1, 'magic');

        self::assertSame('magic', $map->get($event1)->getOrNull());
        self::assertSame('magic', $map[$event1]);
        self::assertSame('magic', $map->get($event2)->getOrNull());
        self::assertSame('magic', $map[$event2]);
        self::assertNull($map->get($event3)->getOrNull());

        self::assertTrue(Set::of($event1)->equals($map->keys()));
        self::assertTrue(Stream::of('magic')->equals($map->values()));
    }

    public function testMapToNativeArray(): void
    {
        self::assertSame(['a' => 'b', 'c' => 'd'], Map::fromArray(['a' => 'b', 'c' => 'd'])->toNativeArray());

        $map = Map::from([Tuple::of(new Event('a', 'same'), 'one'), Tuple::of(new Event('b', 'same'), 'two')]);

        self::assertSame(['a' => 'one', 'b' => 'two'], $map->toNativeArray());

        $map = Map::from([Tuple::of(new Event('a', 'same'), 'one'), Tuple::of(new Event('a', 'same'), 'two')]);

        self::assertSame(['a' => 'two'], $map->toNativeArray());
    }
}
