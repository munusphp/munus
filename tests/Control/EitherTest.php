<?php

declare(strict_types=1);

namespace Munus\Tests\Control;

use Munus\Control\Either\Left;
use Munus\Control\Either\Right;
use Munus\Tests\Stub\Expect;
use Munus\Tests\Stub\Failure;
use Munus\Tests\Stub\Result;
use Munus\Tests\Stub\Success;
use PHPUnit\Framework\TestCase;

final class EitherTest extends TestCase
{
    public function testLeftReturnValue(): void
    {
        $either = Result::generate(false);
        Expect::failure($either->getLeft());
        self::assertEquals(new Failure(), $either->getLeft());
    }

    public function testLeftIsLeft(): void
    {
        self::assertTrue((new Left(null))->isLeft());
        self::assertFalse((new Left(null))->isRight());
    }

    public function testLeftGetOrElse(): void
    {
        self::assertEquals(123, (new Left(456))->getOrElse(123));
    }

    public function testLeftThrowExceptionOnGet(): void
    {
        $this->expectException(\BadMethodCallException::class);
        (new Left(null))->get();
    }

    public function testRightReturnValue(): void
    {
        $either = Result::generate(true);
        Expect::success($either->get());
        self::assertEquals(new Success(), $either->get());
    }

    public function testRightIsRight(): void
    {
        self::assertTrue((new Left(null))->isLeft());
        self::assertFalse((new Left(null))->isRight());
    }

    public function testRightThrowExceptionOnGetLeft(): void
    {
        $this->expectException(\BadMethodCallException::class);
        (new Right(null))->getLeft();
    }

    public function testMapOnLeft(): void
    {
        $either = new Left('error');

        self::assertEquals($either, $either->map('strtoupper'));
    }

    public function testMapOnRight(): void
    {
        $either = new Right('munus');

        self::assertEquals('MUNUS', $either->map('strtoupper')->get());
    }
}
