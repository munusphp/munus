<?php

declare(strict_types=1);

namespace Munus\Tests\Collection;

use Munus\Collection\Iterator;
use Munus\Collection\Iterator\CompositeIterator;
use Munus\Collection\Map;
use Munus\Collection\Set;
use Munus\Exception\NoSuchElementException;
use Munus\Tuple;
use PHPUnit\Framework\TestCase;

final class IteratorTest extends TestCase
{
    public function testSingletonIterator(): void
    {
        $iterator = Iterator::of('one string');

        self::assertTrue($iterator->hasNext());
        self::assertEquals('one string', $iterator->next());
        self::assertFalse($iterator->hasNext());

        $this->expectException(NoSuchElementException::class);
        $iterator->next();
    }

    public function testEmptyIterator(): void
    {
        $iterator = Iterator::empty();

        self::assertFalse($iterator->hasNext());
        $this->expectException(NoSuchElementException::class);
        $iterator->next();
    }

    public function testArrayIterator(): void
    {
        $iterator = Iterator::of(1, 2, 3);
        self::assertTrue($iterator->hasNext());
        self::assertEquals(1, $iterator->next());
        self::assertEquals(2, $iterator->next());
        self::assertEquals(3, $iterator->next());
        self::assertFalse($iterator->hasNext());

        $this->expectException(NoSuchElementException::class);
        $iterator->next();
    }

    public function testMapIterator(): void
    {
        // false in this array is intentionally, its check if hasNext() works correctly
        $iterator = Map::fromArray(['a' => false, 'c' => 'd', 'e' => 'f'])->iterator();
        self::assertTrue($iterator->hasNext());
        self::assertEquals(Tuple::of('a', false), $iterator->next());
        self::assertEquals(Tuple::of('c', 'd'), $iterator->next());
        self::assertEquals(Tuple::of('e', 'f'), $iterator->next());
        self::assertFalse($iterator->hasNext());

        $this->expectException(NoSuchElementException::class);
        $iterator->next();
    }

    public function testCompositeIterator(): void
    {
        $iterator = new CompositeIterator([$first = Iterator::of(1), Iterator::of(2, 3), Iterator::empty(), Iterator::of(4)]);

        self::assertTrue($iterator->hasNext());
        self::assertSame(1, $iterator->current());
        self::assertEquals(1, $iterator->next());
        self::assertEquals(2, $iterator->next());
        self::assertEquals(3, $iterator->next());
        self::assertEquals(4, $iterator->next());
        self::assertFalse($iterator->hasNext());
    }

    public function testReduce(): void
    {
        $iterator = Iterator::of(1, 2, 3);

        self::assertEquals(6, $iterator->reduce(fn (int $a, int $b) => $a + $b));
    }

    public function testReduceOnEmpty(): void
    {
        $this->expectException(NoSuchElementException::class);

        Iterator::empty()->reduce(fn (int $a, int $b) => $a + $b);
    }

    public function testIteratorKey(): void
    {
        $iterator = new Iterator(Set::of(1, 2, 3));

        self::assertSame(0, $iterator->key());
        $iterator->next();
        self::assertSame(1, $iterator->key());
        $iterator->next();
        self::assertSame(2, $iterator->key());
    }
}
