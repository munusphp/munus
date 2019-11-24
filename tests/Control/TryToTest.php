<?php

declare(strict_types=1);

namespace Munus\Tests\Control;

use Munus\Control\TryTo;
use Munus\Tests\Stub\Expect;
use Munus\Tests\Stub\Result;
use Munus\Tests\Stub\Success;
use PHPUnit\Framework\TestCase;

final class TryToTest extends TestCase
{
    public function testTrySuccess(): void
    {
        $try = TryTo::run(function () {return 'success'; });

        self::assertTrue($try->isSuccess());
        self::assertEquals('success', $try->get());
    }

    public function testTryFailure(): void
    {
        $try = TryTo::run(function () {throw new \DomainException('use ddd'); });

        self::assertTrue($try->isFailure());
        self::assertEquals(new \DomainException('use ddd'), $try->getCause());
    }

    public function testGetOrSomething(): void
    {
        /** @var TryTo<Result> $try */
        $try = TryTo::run(function () {throw new \DomainException('use ddd'); });
        $result = $try->getOrElse(new Result());

        Expect::result($result);
        self::assertEquals(new Result(), $result);
        self::assertNull($try->getOrNull());
    }

    public function testEquals(): void
    {
        $exception = new \DomainException('use ddd');
        self::assertTrue(TryTo::run(function (): Success {return new Success(); })->equals(new Success()));
        self::assertTrue(TryTo::run(function () use ($exception) {throw $exception; })->equals($exception));
    }

    public function testMapFailure(): void
    {
        $try = TryTo::run(function () {throw new \DomainException('use ddd'); });
        self::assertEquals(new \DomainException('use ddd'), $try->map('strtolower')->getCause());
    }

    public function testMapWithMapperFailure(): void
    {
        $try = TryTo::run('time');

        self::assertInstanceOf(\TypeError::class, $try->map('strtoupper')->getCause());
    }

    public function testMapWitMapperSuccess(): void
    {
        $try = TryTo::run(function () {return 'success'; });

        self::assertEquals('SUCCESS', $try->map('strtoupper')->get());
    }
}
