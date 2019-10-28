<?php

declare(strict_types=1);

namespace Munus\Collection\Stream;

use Munus\Collection\Stream;

final class Emptƴ extends Stream
{
    public function head()
    {
        throw new \RuntimeException('head of empty stream');
    }

    public function isEmpty(): bool
    {
        return true;
    }
}
