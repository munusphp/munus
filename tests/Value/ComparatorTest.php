<?php

declare(strict_types=1);

namespace Munus\Tests\Value;

use Munus\Collection\GenericList;
use Munus\Value\Comparator;
use PHPUnit\Framework\TestCase;

final class ComparatorTest extends TestCase
{
    public function testComparePrimitives(): void
    {
        self::assertTrue(Comparator::equals(1, 1));
        self::assertTrue(Comparator::equals('1', '1'));

        self::assertFalse(Comparator::equals(0, '0'));
        self::assertFalse(Comparator::equals('1', '0'));
    }

    public function testCompareObjects(): void
    {
        $object1 = GenericList::of(1);
        $object2 = GenericList::of(1);
        $object3 = GenericList::of(1, 2);

        self::assertTrue(Comparator::equals($object1, $object2));
        self::assertTrue(Comparator::equals($object3, $object1->append(2)));
        self::assertFalse(Comparator::equals($object1, $object3));
    }
}
