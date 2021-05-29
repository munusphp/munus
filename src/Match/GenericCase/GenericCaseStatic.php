<?php

declare(strict_types=1);

namespace Munus\Match\GenericCase;

use Munus\Match\GenericCase;

/**
 * @template T
 * @template U
 */
class GenericCaseStatic extends GenericCase
{
    /**
     * @var U
     */
    private $other;

    /**
     * @param T $value
     * @param U $other
     */
    public function __construct($value, $other)
    {
        $this->value = $value;
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
