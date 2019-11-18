<?php

declare(strict_types=1);

namespace Munus\Tests\Control;

use Munus\Control\TryEx;
use Munus\Tests\Stub\Expect;
use Munus\Tests\Stub\Result;
use Munus\Tests\Stub\Success;
use PHPUnit\Framework\TestCase;

final class TryExTest extends TestCase
{
    public function testTrySuccess(): void
    {
        $try = TryEx::of(function () {return 'success'; });

        self::assertTrue($try->isSuccess());
        self::assertEquals('success', $try->get());
    }

    public function testTryFailure(): void
    {
        $try = TryEx::of(function () {throw new \DomainException('use ddd'); });

        self::assertTrue($try->isFailure());
        self::assertEquals(new \DomainException('use ddd'), $try->getCause());
    }

    public function testGetOrSomething(): void
    {
        /** @var TryEx<Result> $try */
        $try = TryEx::of(function () {throw new \DomainException('use ddd'); });
        $result = $try->getOrElse(new Result());

        Expect::result($result);
        self::assertEquals(new Result(), $result);
        self::assertNull($try->getOrNull());
    }

    public function testEquals(): void
    {
        $exception = new \DomainException('use ddd');
        self::assertTrue(TryEx::of(function (): Success {return new Success(); })->equals(new Success()));
        self::assertTrue(TryEx::of(function () use ($exception) {throw $exception; })->equals($exception));
    }

    public function testMapFailure(): void
    {
        $try = TryEx::of(function () {throw new \DomainException('use ddd'); });
        self::assertEquals(new \DomainException('use ddd'), $try->map('strtolower')->getCause());
    }

    public function testMapWithMapperFailure(): void
    {
        $try = TryEx::of('time');

        self::assertInstanceOf(\TypeError::class, $try->map('strtoupper')->getCause());
    }

    public function testMapWitMapperSuccess(): void
    {
        $try = TryEx::of(function () {return 'success'; });

        self::assertEquals('SUCCESS', $try->map('strtoupper')->get());
    }
}
