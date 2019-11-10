<?php

declare(strict_types=1);

namespace Munus\Tests\Collection;

use Munus\Collection\Iterator;
use Munus\Exception\NoSuchElementException;
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
}
