<?php

declare(strict_types=1);

namespace Munus\Tests\Control;

use Munus\Collection\Map;
use Munus\Collection\Stream;
use Munus\Collection\Stream\Collectors;
use Munus\Control\Either;
use Munus\Control\Either\Left;
use Munus\Control\Either\Right;
use Munus\Control\Option;
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
        self::assertEquals('MUNUS', Either::right('munus')->map('strtoupper')->get());
    }

    public function testLeftPeek(): void
    {
        $either = Either::left('error');
        self::assertSame($either, $either->peek(function () {throw new \RuntimeException('this will not happen'); }));
    }

    public function testRightPeek(): void
    {
        $check = null;
        $either = Either::right(42);
        self::assertSame($either, $either->peek(function ($value) use (&$check) {$check = $value; }));
        self::assertEquals(42, $check);
    }

    public function testEitherCollect(): void
    {
        self::assertTrue(Map::fromArray(['a' => 'b'])->equals(
            Either::right('b')->collect(Collectors::toMap(function () {return 'a'; }))
        ));
        self::assertTrue(Map::empty()->equals(
            Either::left('b')->collect(Collectors::toMap(function () {return 'a'; }))
        ));
    }

    public function testToOption(): void
    {
        self::assertTrue(Option::of('right')->equals(Either::right('right')->toOption()));
        self::assertTrue(Option::none()->equals(Either::left('left')->toOption()));
    }

    public function testToStream(): void
    {
        self::assertTrue(Stream::of('right')->equals(
            Either::right('right')->toStream()
        ));
        self::assertTrue(Stream::empty()->equals(
            Either::left('right')->toStream()
        ));
    }

    public function testToArray(): void
    {
        self::assertEquals(['a'], Either::right('a')->toArray());
        self::assertEquals([], Either::left('a')->toArray());
    }

    public function testEitherIteratorLeft(): void
    {
        $iterator = Either::left('left')->iterator();

        self::assertFalse($iterator->hasNext());
    }

    public function testEitherIteratorRight(): void
    {
        $iterator = Either::right('right')->iterator();

        self::assertTrue($iterator->hasNext());
        self::assertSame('right', $iterator->next());
        self::assertFalse($iterator->hasNext());
    }
}
