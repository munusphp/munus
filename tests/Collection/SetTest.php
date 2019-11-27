<?php

declare(strict_types=1);

namespace Munus\Tests\Collection;

use Munus\Collection\Set;
use PHPUnit\Framework\TestCase;

final class SetTest extends TestCase
{
    public function testSetContains(): void
    {
        $set = Set::of('alpha', 'beta');

        self::assertTrue($set->contains('alpha'));
        self::assertFalse($set->contains('gamma'));
    }

    public function testSetAdd(): void
    {
        $set = Set::ofAll(['alpha', 'beta']);
        $new = $set->add('gamma');

        self::assertTrue($new->contains('gamma'));
        self::assertTrue($set !== $new);
        self::assertEquals(3, $new->length());
    }

    public function testSetRemove(): void
    {
        $set = Set::ofAll(['alpha', 'beta', 'gamma']);
        $new = $set->remove('beta');

        self::assertFalse($new->contains('beta'));
        self::assertTrue($set !== $new);
        self::assertEquals(2, $new->length());
    }

    public function testSetMap(): void
    {
        self::assertTrue(
            Set::ofAll([1, 2, 3])->map(function (int $int): int {
                return $int * 2;
            })->equals(Set::ofAll([2, 4, 6]))
        );
    }

    public function testSetForEach(): void
    {
        $counter = 0;
        Set::of(1, 2, 3)->forEach(function (int $x) use (&$counter) {
            self::assertEquals(++$counter, $x);
        });
        self::assertEquals(3, $counter);
    }

    public function testSetReduce(): void
    {
        self::assertEquals(10, Set::of(1, 2, 3, 4)->reduce(function (int $a, int $b): int {return $a + $b; }));
    }

    public function testListFold(): void
    {
        self::assertEquals(6, Set::of('a', 'bbb', 'cc')->fold(0, function (int $a, string $b): int {return $a + mb_strlen($b); }));
    }

    public function testSetUnion(): void
    {
        $set = Set::ofAll(['alpha', 'beta', 'gamma']);
        $new = $set->union(Set::ofAll(['beta', 'gamma', 'delta']));

        self::assertTrue($new->contains('delta'));
        self::assertTrue($set !== $new);
        self::assertEquals(4, $new->length());
    }

    public function testSetCanHoldObjects(): void
    {
        $set = Set::ofAll([Set::of(1, 2, 3), Set::of(4, 5, 6)]);

        self::assertEquals(2, $set->length());
        self::assertFalse($set->contains(new \stdClass()));
        self::assertTrue($set->contains(Set::of(1, 2, 3)));
    }
}
