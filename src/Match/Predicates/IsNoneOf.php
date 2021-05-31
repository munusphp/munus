<?php

declare(strict_types=1);

namespace Munus\Match\Predicates;

use Munus\Collection\GenericList;
use Munus\Match\Predicate;

/**
 * @template T
 */
class IsNoneOf implements Predicate
{
    /**
     * @var GenericList<Predicate>
     */
    private $predicates;

    public function __construct(Predicate ...$predicates)
    {
        $this->predicates = GenericList::ofAll($predicates);
    }

    /**
     * @param T $value
     */
    public function meet($value): bool
    {
        return $this->predicates
            ->filter(function (Predicate $predicate) use ($value): bool {
                return $predicate->meet($value);
            })->isEmpty();
    }
}
