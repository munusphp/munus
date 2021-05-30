<?php

declare(strict_types=1);

namespace Munus\Match\Predicates;

use Munus\Collection\GenericList;
use Munus\Match\Is;

/**
 * @template T
 */
class IsAnyOf extends Is
{
    /**
     * @var GenericList<Is>
     */
    private $predicates;

    public function __construct(Is ...$predicates)
    {
        $this->predicates = GenericList::ofAll($predicates);
    }

    /**
     * @param T $value
     */
    public function meet($value): bool
    {
        return !$this->predicates
            ->filter(function (Is $predicate) use ($value): bool {
                return $predicate->meet($value);
            })->isEmpty();
    }
}
