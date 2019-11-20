<?php

declare(strict_types=1);

namespace Munus\Tests\Collection;

use Munus\Collection\GenericList;
use Munus\Collection\Set;
use Munus\Collection\Stream;
use PHPUnit\Framework\TestCase;

final class TraversableTest extends TestCase
{
    public function testStrictEquals(): void
    {
        self::assertFalse(
            GenericList::of(1, 2, 3)->equals(GenericList::of(1, '2', 3))
        );
    }

    public function testTraversableImplementationAgnosticEquals(): void
    {
        self::assertTrue(
            GenericList::of(1, 2, 3)->equals(Stream::of(1, 2, 3))
        );
        self::assertTrue(
            Set::of('a', 'b')->equals(GenericList::ofAll(['a', 'b']))
        );
    }
}
