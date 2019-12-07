<?php

declare(strict_types=1);

namespace Munus\Tests\Collection;

use Munus\Collection\Iterator;
use Munus\Collection\Map;
use Munus\Exception\NoSuchElementException;
use Munus\Tuple;
use PHPStan\Testing\TestCase;

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
}
