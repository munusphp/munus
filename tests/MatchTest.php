<?php

declare(strict_types=1);

namespace Munus\Tests;

use Munus\Exception\MatchNotFoundException;
use function Munus\Match\caseCall;
use function Munus\Match\caseOf;
use function Munus\Match\defaultCall;
use function Munus\Match\defaultOf;
use function Munus\Match\matchValue;
use function Munus\Match\Predicates\isAllOf;
use function Munus\Match\Predicates\isAnyOf;
use function Munus\Match\Predicates\isIn;
use function Munus\Match\Predicates\isInstanceOf;
use function Munus\Match\Predicates\isNoneOf;
use function Munus\Match\Predicates\isNotNull;
use function Munus\Match\Predicates\isNull;
use function Munus\Match\Predicates\isValue;
use PHPUnit\Framework\TestCase;

class MatchTest extends TestCase
{
    public function testMatch(): void
    {
        $result = matchValue('value')->of(
            caseCall('value', function (string $value) { return 'matched '.$value; }),
            defaultCall(function (string $value) { return 'default '.$value; })
        );

        self::assertEquals('matched value', $result);
    }

    public function testDefaultMatch(): void
    {
        $result = matchValue('value')->of(
            caseCall('none', function (string $value) { return 'matched '.$value; }),
            defaultCall(function (string $value) { return 'default '.$value; })
        );

        self::assertEquals('default value', $result);
    }

    public function testMatchStaticValue(): void
    {
        $result = matchValue('value')->of(
            caseOf('value', 'match'),
            defaultOf('default')
        );

        self::assertEquals('match', $result);
    }

    public function testDefaultMatchStaticValue(): void
    {
        $result = matchValue('value')->of(
            caseOf('none', 'match'),
            defaultOf('default')
        );

        self::assertEquals('default', $result);
    }

    public function testNonMatch(): void
    {
        self::expectException(MatchNotFoundException::class);

        matchValue('value')->of(
            caseOf('none', 'other')
        );
    }

    public function testValueMatch(): void
    {
        $result = matchValue('value')->of(
            caseOf(isValue('value'), 'match'),
            defaultOf('default')
        );

        self::assertEquals('match', $result);
    }

    public function testInValuesMatch(): void
    {
        $result = matchValue('value')->of(
            caseOf(isIn(['value', 'another']), 'match'),
            defaultOf('default')
        );

        self::assertEquals('match', $result);
    }

    public function testInstanceMatch(): void
    {
        $result = matchValue(new \DateTime())->of(
            caseOf(isInstanceOf(\DateTime::class), 'match'),
            defaultOf('default')
        );

        self::assertEquals('match', $result);
    }

    public function testMatchAllOf(): void
    {
        $result = matchValue(2)->of(
            caseOf(isAllOf(isIn([1, 2]), isIn([2, 3])), 'match'),
            defaultOf('default')
        );

        self::assertEquals('match', $result);

        $result = matchValue(1)->of(
            caseOf(isAllOf(isIn([1, 2]), isIn([2, 3])), 'match'),
            defaultOf('default')
        );

        self::assertEquals('default', $result);
    }

    public function testMatchNoneOf(): void
    {
        $result = matchValue(2)->of(
            caseOf(isNoneOf(isIn([1, 2]), isIn([2, 3])), 'match'),
            defaultOf('default')
        );

        self::assertEquals('default', $result);

        $result = matchValue(1)->of(
            caseOf(isNoneOf(isIn([4, 2]), isIn([2, 3])), 'match'),
            defaultOf('default')
        );

        self::assertEquals('match', $result);
    }

    public function testMatchAnyOf(): void
    {
        $result = matchValue(2)->of(
            caseOf(isAnyOf(isIn([1, 2]), isIn([4, 3])), 'match'),
            defaultOf('default')
        );

        self::assertEquals('match', $result);

        $result = matchValue(1)->of(
            caseOf(isAnyOf(isIn([4, 2]), isIn([2, 3])), 'match'),
            defaultOf('default')
        );

        self::assertEquals('default', $result);
    }

    public function testMatchNull(): void
    {
        $result = matchValue(null)->of(
            caseOf(isNull(), 'match'),
            defaultOf('default')
        );

        self::assertEquals('match', $result);
    }

    public function testMatchNotNull(): void
    {
        $result = matchValue(1)->of(
            caseOf(isNotNull(), 'match'),
            defaultOf('default')
        );

        self::assertEquals('match', $result);
    }
}
