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
        $set = Set::ofAll([new \stdClass(), new \stdClass()]);

        self::assertEquals(2, $set->length());
        self::assertFalse($set->contains(new \stdClass()));
    }
}
