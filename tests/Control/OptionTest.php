<?php

declare(strict_types=1);

namespace Munus\Tests\Control;

use Munus\Collection\Set;
use Munus\Collection\Stream;
use Munus\Collection\Stream\Collectors;
use Munus\Control\Option;
use Munus\Exception\NoSuchElementException;
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
        self::assertSame('string', $option->getOrElse('string'));
    }

    public function testMap(): void
    {
        self::assertEquals('MUNUS', Option::of('munus')->map('strtoupper')->get());
    }

    public function testFlatMapSome(): void
    {
        $option = Option::of('munus');

        self::assertEquals('munus', $option->flatMap(fn (string $value) => Option::some($value))->get());
        self::assertEquals('2', $option->flatMap(fn () => Option::of('2'))->get());
        self::assertTrue(Option::none()->equals($option->flatMap(fn () => Option::none())));
    }

    public function testFlatMapNone(): void
    {
        $option = Option::none();

        self::assertTrue(Option::none()->equals($option->flatMap(fn (string $value) => Option::some($value))));
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

    public function testSomePeek(): void
    {
        $check = null;
        $option = Option::of(43);
        self::assertSame($option, $option->peek(function ($value) use (&$check) {$check = $value; }));
        self::assertEquals(43, $check);
    }

    public function testNonePeek(): void
    {
        $option = Option::none();
        self::assertSame($option, $option->peek(function () {throw new \RuntimeException('this will not happen'); }));
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

    public function testOptionToArray(): void
    {
        self::assertEquals(['a'], Option::some('a')->toArray());
        self::assertEquals([], Option::none()->toArray());
    }

    public function testOptionIsPresent(): void
    {
        self::assertTrue(Option::of(43)->isPresent());
        self::assertTrue(Option::some(null)->isPresent());
        self::assertFalse(Option::none()->isPresent());
    }

    public function testOptionIfPresent(): void
    {
        Option::none()->ifPresent(fn ($v) => throw new \RuntimeException('impossible is nothing'));
        Option::of('a')->ifPresent(fn ($v) => self::assertSame('a', $v));
    }

    public function testOptionOfNoneGet(): void
    {
        $this->expectException(NoSuchElementException::class);

        Option::none()->get();
    }
}
