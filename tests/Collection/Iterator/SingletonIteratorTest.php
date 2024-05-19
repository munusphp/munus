<?php

declare(strict_types=1);

namespace Munus\Tests\Collection\Iterator;

use Munus\Collection\Iterator\SingletonIterator;
use Munus\Exception\NoSuchElementException;
use PHPUnit\Framework\TestCase;

final class SingletonIteratorTest extends TestCase
{
    public function testCurrentIfThereIsNoNext(): void
    {
        $iterator = new SingletonIterator('a');
        $iterator->next();

        $this->expectException(NoSuchElementException::class);

        $iterator->current();
    }

    public function testRewind(): void
    {
        $iterator = new SingletonIterator('a');
        self::assertSame('a', $iterator->next());
        self::assertFalse($iterator->hasNext());

        $iterator->rewind();

        self::assertTrue($iterator->hasNext());
        self::assertTrue($iterator->valid());
        self::assertSame(0, $iterator->key());
        self::assertSame('a', $iterator->next());
        self::assertFalse($iterator->hasNext());
    }
}
