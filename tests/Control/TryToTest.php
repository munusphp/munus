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

    public function testRecover(): void
    {
        $try = TryTo::run(function () {throw new \DomainException('use ddd'); });

        self::assertEquals('ddd implemented', $try->recover(\DomainException::class, function () {return 'ddd implemented'; })->get());
    }

    public function testMultipleRecover(): void
    {
        $value = TryTo::run(function () {throw new \DomainException('use ddd'); })
        ->recover(\RuntimeException::class, function () {return 'runtime handled'; })
        ->recover(\DomainException::class, function () {return 'domain handled'; })
        ->get();

        self::assertEquals('domain handled', $value);
    }

    public function testAndThenWithSuccess(): void
    {
        $control = 1;
        TryTo::run(function () {
            return 2;
        })->andThen(function (int $first) use (&$control) {
            $control += $first;
        });

        self::assertEquals($control, 3);
    }

    public function testAndThenWithFailure(): void
    {
        $try = TryTo::run(function () {
            throw new \DomainException('use ddd');
        })->andThen(function (int $first) use (&$control) {
            throw new \RuntimeException('this should not happen');
        });

        self::assertEquals(new \DomainException('use ddd'), $try->getCause());
    }

    public function testAndThenThrowException(): void
    {
        $try = TryTo::run(function () {
            return 42;
        })->andThen(function (int $first) use (&$control) {
            throw new \RuntimeException('and then fails');
        });

        self::assertEquals(new \RuntimeException('and then fails'), $try->getCause());
    }

    public function testFinallyWithSuccess(): void
    {
        $control = 1;
        TryTo::run(function () {
            return 'result';
        })->andFinally(function () use (&$control) {
            ++$control;
        });

        self::assertEquals(2, $control);
    }

    public function testFinallyWithFailure(): void
    {
        $control = 1;
        TryTo::run(function () {
            throw new \DomainException('ACID not supported');
        })->andFinally(function () use (&$control) {
            ++$control;
        });

        self::assertEquals(2, $control);
    }

    public function testFinallyThrowException(): void
    {
        $try = TryTo::run(function () {
            return 'ACID supported';
        })->andFinally(function () use (&$control) {
            throw new \DomainException('ACID not supported');
        });

        self::assertEquals(new \DomainException('ACID not supported'), $try->getCause());
    }

    public function testOnSuccess(): void
    {
        $control = 1;
        TryTo::run(function () {
            return 42;
        })->onSuccess(function (int $value) use (&$control) {
            $control += $value;
        });

        self::assertEquals(43, $control);
    }

    public function testOnSuccessWithFailure(): void
    {
        $control = 1;
        TryTo::run(function () {
            throw new \DomainException('ACID not supported');
        })->onSuccess(function () use (&$control) {
            ++$control;
        });

        self::assertEquals(1, $control);
    }

    public function testOnFailure(): void
    {
        $control = 1;
        TryTo::run(function () {
            throw new \RuntimeException('PHP not supported');
        })->onFailure(function (\Throwable $throwable) use (&$control) {
            ++$control;
            self::assertInstanceOf(\RuntimeException::class, $throwable);
        })->onSuccess(function () use (&$control) {
            --$control;
        });

        self::assertEquals(2, $control);
    }

    public function testOnFailureWithFailure(): void
    {
        $control = 1;
        TryTo::run(function () {
            return 'success';
        })->onFailure(function () use (&$control) {
            ++$control;
        });

        self::assertEquals(1, $control);
    }

    public function testOnSpecificFailure(): void
    {
        $control = 1;
        TryTo::run(function () {
            throw new \LogicException('AI is not reasonable');
        })->onSpecificFailure(\RuntimeException::class, function () use (&$control) {
            $control += 5;
        })->onSpecificFailure(\LogicException::class, function (\LogicException $exception) use (&$control) {
            ++$control;
            self::assertEquals('AI is not reasonable', $exception->getMessage());
        });

        self::assertEquals(2, $control);
    }
}
