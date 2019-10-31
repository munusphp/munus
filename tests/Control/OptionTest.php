<?php

declare(strict_types=1);

namespace Munus\Tests\Control;

use Munus\Control\Option;
use Munus\Tests\Stub\Expect;
use Munus\Tests\Stub\Success;
use PHPUnit\Framework\TestCase;

final class OptionTest extends TestCase
{
    public function testSome(): void
    {
        $option = Option::of('foo');
        self::assertFalse($option->isEmpty());
    }

    public function testNone(): void
    {
        $option = Option::of(null);
        self::assertTrue($option->isEmpty());
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
}
