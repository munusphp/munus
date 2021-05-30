<?php

declare(strict_types=1);

namespace Munus\Match\Predicates;

use Munus\Match\Is;

/**
 * @template T
 */
class IsNull extends Is
{
    /**
     * @param T $value
     */
    public function equals($value): bool
    {
        return is_null($value);
    }
}
