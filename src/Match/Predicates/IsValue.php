<?php

declare(strict_types=1);

namespace Munus\Match\Predicates;

use Munus\Match\Is;

/**
 * @template T
 */
class IsValue extends Is
{
    /**
     * @var T
     */
    private $value;

    /**
     * @param T $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param T $value
     */
    public function equals($value): bool
    {
        return $this->value === $value;
    }
}
