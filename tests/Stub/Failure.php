<?php

declare(strict_types=1);

namespace Munus\Tests\Stub;

final class Failure
{
    public function reason(): string
    {
        return 'by design';
    }
}
