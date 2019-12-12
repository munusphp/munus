<?php

declare(strict_types=1);

namespace Munus\Tests\Control;

use Munus\Collection\Set;
use Munus\Collection\Stream;
use Munus\Collection\Stream\Collectors;
use Munus\Control\Option;
use Munus\Lazy;
use Munus\Tests\Stub\Expect;
use Munus\Tests\Stub\Success;
use PHPUnit\Framework\TestCase;

final class OptionTest extends TestCase
{
    public function testSome(): void
    {
        $option = Option::of('foo');
        self::assertFalse($option->isEmpty());

        $option = Option::some('bar');
        self::assertFalse($option->isEmpty());
    }

    public function testNone(): void
    {
        $option = Option::of(null);
        self::assertTrue($option->isEmpty());
    }

    public function testOptionWhen(): void
    {
        $option = Option::when(true, 5);
        self::assertEquals(5, $option->getOrElse(42));

        $option = Option::when(false, 5);
        self::assertEquals(42, $option->getOrElse(42));
    }

    public function testDocblock(): void
    {
        /** @var Option<Success> $option */
        $option = Option::of(new Success());
        Expect::success($option->get());
        self::assertEquals(new Success(), $option->get());
    }

    public function testGetOrSomething(): void
    {
        /** @var Option<string> $option */
        $option = Option::of(null);
        self::assertNull($option->getOrNull());
        self::assertTrue($option->getOrElse(true));
    }

    public function testMap(): void
    {
        $option = Option::of('munus');

        self::assertInstanceOf(Option::class, $option->map('strtolower'));
        self::assertEquals('MUNUS', $option->map('strtoupper')->get());
    }

    public function testOptionForEach(): void
    {
        $lazy = Lazy::ofValue(1);
        Option::of($lazy)->forEach(function (Lazy $lazy) {$lazy->get(); });

        self::assertTrue($lazy->isEvaluated());
    }

    public function testContains(): void
    {
        self::assertTrue(Option::of('munus')->contains('munus'));
        self::assertFalse(Option::of('munus')->contains('coffe'));
    }

    public function testGetOrElseThrowOnSome(): void
    {
        self::assertEquals(1, Option::of(1)->getOrElseThrow(new \RuntimeException('bad architecture')));
    }

    public function testGetOrElseThrowOnNone(): void
    {
        $this->expectException(\RuntimeException::class);

        Option::none()->getOrElseThrow(new \RuntimeException('bad architecture'));
    }

    public function testGetOrElseTryOnSome(): void
    {
        self::assertEquals(1, Option::of(1)->getOrElseTry(function () {return 2; }));
    }

    public function testGetOrElseTryOnNone(): void
    {
        self::assertEquals(2, Option::none()->getOrElseTry(function () {return 2; }));
    }

    public function testExists(): void
    {
        self::assertTrue(Option::of(7)->exists(function (int $x) {return $x % 7 === 0; }));
        self::assertFalse(Option::of(9)->exists(function (int $x) {return $x % 7 === 0; }));
    }

    public function testForAll(): void
    {
        self::assertTrue(Option::of(7)->forAll(function (int $x) {return $x % 7 === 0; }));
        self::assertFalse(Option::of(9)->forAll(function (int $x) {return $x % 7 === 0; }));
    }

    public function testOptionCollect(): void
    {
        self::assertTrue(Set::of('munus')->equals(
            Option::of('munus')->collect(Collectors::toSet())
        ));
        self::assertTrue(Set::empty()->equals(
            Option::none()->collect(Collectors::toSet())
        ));
    }

    public function testOptionEquals(): void
    {
        self::assertTrue(Option::none()->equals(Option::none()));
        self::assertFalse(Option::none()->equals(Option::some('none')));
        self::assertFalse(Option::none()->equals('none'));
    }

    public function testOptionToOption(): void
    {
        $option = Option::of(42);
        self::assertSame($option, $option->toOption());
    }

    public function testOptionToStream(): void
    {
        self::assertTrue(Stream::of(42)->equals(Option::of(42)->toStream()));
    }
}
