<?php

declare(strict_types=1);

namespace Munus\Tests;

use Munus\Exception\MatchNotFoundException;
use Munus\GenericMatch;
use Munus\Match\DefaultCase;
use Munus\Match\GenericCase;
use Munus\Match\Is;
use PHPUnit\Framework\TestCase;

class MatchTest extends TestCase
{
    public function testMatch(): void
    {
        $result = GenericMatch::value('value')->of(
            GenericCase::of('value', function (string $value) { return 'matched '.$value; }),
            DefaultCase::of(function (string $value) { return 'default '.$value; })
        );

        self::assertEquals('matched value', $result);
    }

    public function testDefaultMatch(): void
    {
        $result = GenericMatch::value('value')->of(
            GenericCase::of('none', function (string $value) { return 'matched '.$value; }),
            DefaultCase::of(function (string $value) { return 'default '.$value; })
        );

        self::assertEquals('default value', $result);
    }

    public function testMatchStaticValue(): void
    {
        $result = GenericMatch::value('value')->of(
            GenericCase::ofStatic('value', 'match'),
            DefaultCase::ofStatic('default')
        );

        self::assertEquals('match', $result);
    }

    public function testDefaultMatchStaticValue(): void
    {
        $result = GenericMatch::value('value')->of(
            GenericCase::ofStatic('none', 'match'),
            DefaultCase::ofStatic('default')
        );

        self::assertEquals('default', $result);
    }

    public function testNonMatch(): void
    {
        self::expectException(MatchNotFoundException::class);

        GenericMatch::value('value')->of(
            GenericCase::ofStatic('none', 'other')
        );
    }

    public function testValueMatch(): void
    {
        $result = GenericMatch::value('value')->of(
            GenericCase::ofStatic(Is::value('value'), 'match'),
            DefaultCase::ofStatic('other')
        );

        self::assertEquals('match', $result);
    }

    public function testInValuesMatch(): void
    {
        $result = GenericMatch::value('value')->of(
            GenericCase::ofStatic(Is::in(['value', 'another']), 'match'),
            DefaultCase::ofStatic('other')
        );

        self::assertEquals('match', $result);
    }

    public function testInstanceMatch(): void
    {
        $result = GenericMatch::value(new \DateTime())->of(
            GenericCase::ofStatic(Is::instance(\DateTime::class), 'match'),
            DefaultCase::ofStatic('other')
        );

        self::assertEquals('match', $result);
    }

    public function testMatchAllOf(): void
    {
        $result = GenericMatch::value(2)->of(
            GenericCase::ofStatic(Is::allOf(Is::in([1, 2]), Is::in([2, 3])), 'match'),
            DefaultCase::ofStatic('other')
        );

        self::assertEquals('match', $result);

        $result = GenericMatch::value(1)->of(
            GenericCase::ofStatic(Is::allOf(Is::in([1, 2]), Is::in([2, 3])), 'match'),
            DefaultCase::ofStatic('other')
        );

        self::assertEquals('other', $result);
    }

    public function testMatchNoneOf(): void
    {
        $result = GenericMatch::value(2)->of(
            GenericCase::ofStatic(Is::noneOf(Is::in([1, 2]), Is::in([2, 3])), 'match'),
            DefaultCase::ofStatic('other')
        );

        self::assertEquals('other', $result);

        $result = GenericMatch::value(1)->of(
            GenericCase::ofStatic(Is::noneOf(Is::in([4, 2]), Is::in([2, 3])), 'match'),
            DefaultCase::ofStatic('other')
        );

        self::assertEquals('match', $result);
    }

    public function testMatchAnyOf(): void
    {
        $result = GenericMatch::value(2)->of(
            GenericCase::ofStatic(Is::anyOf(Is::in([1, 2]), Is::in([4, 3])), 'match'),
            DefaultCase::ofStatic('other')
        );

        self::assertEquals('match', $result);

        $result = GenericMatch::value(1)->of(
            GenericCase::ofStatic(Is::anyOf(Is::in([4, 2]), Is::in([2, 3])), 'match'),
            DefaultCase::ofStatic('other')
        );

        self::assertEquals('other', $result);
    }

    public function testMatchNull(): void
    {
        $result = GenericMatch::value(null)->of(
            GenericCase::ofStatic(Is::null(), 'match'),
            DefaultCase::ofStatic('other')
        );

        self::assertEquals('match', $result);
    }

    public function testMatchNotNull(): void
    {
        $result = GenericMatch::value(1)->of(
            GenericCase::ofStatic(Is::notNull(), 'match'),
            DefaultCase::ofStatic('other')
        );

        self::assertEquals('match', $result);
    }
}
