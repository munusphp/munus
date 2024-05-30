<?php

declare(strict_types=1);

namespace Munus\Tests;

use Munus\Collection\Set;
use Munus\Collection\Stream;
use Munus\Collection\Stream\Collectors;
use Munus\Control\Option;
use Munus\Lazy;
use PHPUnit\Framework\TestCase;

final class LazyTest extends TestCase
{
    public function testNoEvaluatedByDefault(): void
    {
        $lazy = Lazy::of(function () {return 123; });

        self::assertFalse($lazy->isEvaluated());
    }

    public function testEvaluation(): void
    {
        $lazy = Lazy::of(function () {return 'Munus is awesome'; });

        self::assertEquals('Munus is awesome', $lazy->get());
        self::assertTrue($lazy->isEvaluated());
    }

    public function testInvoke(): void
    {
        $lazy = Lazy::of(function () {return 'Munus is awesome'; });

        self::assertEquals('Munus is awesome', $lazy());
        self::assertTrue($lazy->isEvaluated());
    }

    public function testEvaluationOnlyOnce(): void
    {
        $lazy = Lazy::of(function (): int {return random_int(1, 1000); });

        self::assertEquals($lazy->get(), $lazy->get());
    }

    public function testMap(): void
    {
        $lazy = Lazy::of(function () {return 4; });

        self::assertSame(5, $lazy->map(fn (int $x) => $x + 1)->get());
        self::assertEquals(2, $lazy->map('sqrt')->get()); // sqrt returns float
    }

    public function testCollect(): void
    {
        self::assertTrue(Set::of(42)->equals(
            Lazy::ofValue(42)->collect(Collectors::toSet())
        ));
    }

    public function testToOption(): void
    {
        $lazy = Lazy::of(function () {return 4; });
        self::assertTrue(Option::of(4)->equals($lazy->toOption()));
    }

    public function testToStream(): void
    {
        $lazy = Lazy::of(function () {return 'munus'; });

        self::assertTrue(Stream::of('munus')->equals(
            $lazy->toStream()
        ));
    }

    public function testToArray(): void
    {
        $lazy = Lazy::of(function () {return 'munus'; });

        self::assertEquals(['munus'], $lazy->toArray());
    }

    public function testLazyPeek(): void
    {
        $check = null;
        $lazy = Lazy::of(function () {return 'munus'; });

        self::assertSame($lazy, $lazy->peek(function ($value) use (&$check) {$check = $value; }));
        self::assertEquals('munus', $check);
        self::assertTrue($lazy->isEvaluated());
    }

    public function testLazyIterator(): void
    {
        $iterator = Lazy::of(function () {return 'munus'; })->iterator();

        self::assertTrue($iterator->hasNext());
        self::assertSame('munus', $iterator->next());
        self::assertFalse($iterator->hasNext());
    }
}
