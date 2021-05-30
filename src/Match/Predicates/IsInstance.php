<?php

declare(strict_types=1);

namespace Munus\Match\Predicates;

use Munus\Match\Is;

/**
 * @template T
 */
class IsInstance extends Is
{
    /**
     * @var string
     */
    private $className;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    /**
     * @param T $value
     */
    public function equals($value): bool
    {
        return $value instanceof $this->className;
    }
}
