<?php

declare(strict_types=1);

namespace Munus\Functiοn;

/**
 * @template T
 */
interface Supplier
{
    /**
     * @return T
     */
    public function get();
}
