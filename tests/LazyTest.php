<?php

declare(strict_types=1);

namespace Munus\Tests;

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

    public function testEvaluationOnlyOnce(): void
    {
        $lazy = Lazy::of(function (): int {return random_int(1, 1000); });

        self::assertEquals($lazy->get(), $lazy->get());
    }

    public function testMap(): void
    {
        $lazy = Lazy::of(function () {return 4; });

        self::assertInstanceOf(Lazy::class, $lazy->map('range'));
        self::assertEquals(2, $lazy->map('sqrt')->get());
    }
}
