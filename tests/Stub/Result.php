<?php

declare(strict_types=1);

namespace Munus\Tests\Stub;

use Munus\Control\Either;
use Munus\Control\Either\Left;
use Munus\Control\Either\Right;

final class Result
{
    /**
     * @return Either<Failure,Success>
     * @psalm-suppress MixedReturnTypeCoercion
     */
    public static function generate(bool $success = true): Either
    {
        if ($success === true) {
            return new Right(new Success());
        }

        return new Left(new Failure());
    }
}
