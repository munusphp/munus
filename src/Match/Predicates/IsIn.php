<?php

declare(strict_types=1);

namespace Munus\Match\Predicates;

use Munus\Match\Is;

/**
 * @template T
 */
class IsIn extends Is
{
    /**
     * @var iterable
     */
    private $values;

    public function __construct(iterable $values)
    {
        $this->values = $values;
    }

    /**
     * @param T $value
     */
    public function equals($value): bool
    {
        return in_array($value, (array) $this->values, true);
    }
}
