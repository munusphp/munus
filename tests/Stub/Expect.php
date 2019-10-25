<?php

declare(strict_types=1);

namespace Munus\Tests\Stub;

/**
 * Stubbed class that allow us to check if psalm annotation works correctly
 * with this check we can allow to make: Either<A,B> or Try<A>.
 */
final class Expect
{
    public static function success(Success $success): void
    {
    }

    public static function failure(Failure $failure): void
    {
    }

    public static function result(Result $result): void
    {
    }
}
