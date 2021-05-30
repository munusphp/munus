<?php

declare(strict_types=1);

namespace Munus\Match\Predicates;

use Munus\Match\Predicate;

/**
 * @template T
 */
class IsInstance implements Predicate
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
    public function meet($value): bool
    {
        return $value instanceof $this->className;
    }
}
