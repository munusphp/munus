<?php

declare(strict_types=1);

namespace Munus\Tests\Control;

use Munus\Control\Trƴ;
use Munus\Tests\Stub\Expect;
use Munus\Tests\Stub\Result;
use Munus\Tests\Stub\Success;
use PHPUnit\Framework\TestCase;

final class TrƴTest extends TestCase
{
    public function testTrySuccess(): void
    {
        $try = Trƴ::of(function () {return 'success'; });

        self::assertTrue($try->isSuccess());
        self::assertEquals('success', $try->get());
    }

    public function testTryFailure(): void
    {
        $try = Trƴ::of(function () {throw new \DomainException('use ddd'); });

        self::assertTrue($try->isFailure());
        self::assertEquals(new \DomainException('use ddd'), $try->getCause());
    }

    public function testGetOrSomething(): void
    {
        /** @var Trƴ<Result> $try */
        $try = Trƴ::of(function () {throw new \DomainException('use ddd'); });
        $result = $try->getOrElse(new Result());

        Expect::result($result);
        self::assertEquals(new Result(), $result);
        self::assertNull($try->getOrNull());
    }

    public function testEquals(): void
    {
        $exception = new \DomainException('use ddd');
        self::assertTrue(Trƴ::of(function (): Success {return new Success(); })->equals(new Success()));
        self::assertTrue(Trƴ::of(function () use ($exception) {throw $exception; })->equals($exception));
    }
}
