<?php

declare(strict_types=1);

namespace Munus\Match\DefaultCase;

use Munus\Match\DefaultCase;

/**
 * @template T
 * @template U
 */
class DefaultCaseStatic extends DefaultCase
{
    /**
     * @var U
     */
    private $other;

    /**
     * @param U $other
     */
    public function __construct($other)
    {
        $this->other = $other;
    }

    /**
     * @param T $value
     *
     * @return U
     */
    public function apply($value)
    {
        return $this->other;
    }
}
