<?php

declare(strict_types=1);

namespace Munus\Match\Predicates;

use Munus\Collection\GenericList;
use Munus\Match\Is;

/**
 * @template T
 */
class IsAllOf extends Is
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
    public function equals($value): bool
    {
        $predicatesList = GenericList::ofAll($this->predicates);

        return $this->predicates->count(function (Is $predicate) use ($value): bool {
            return $predicate->equals($value);
        }) === $this->predicates->length();
    }
}
