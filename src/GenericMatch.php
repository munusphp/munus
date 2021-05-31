<?php

declare(strict_types=1);

namespace Munus;

use Munus\Collection\GenericList;
use Munus\Exception\MatchNotFoundException;
use Munus\Match\MatchCase;

/**
 * @template T
 */
class GenericMatch
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
     * @psalm-template U
     *
     * @param U $value
     *
     * @return GenericMatch<U>
     */
    public static function value($value): GenericMatch
    {
        return new GenericMatch($value);
    }

    /**
     * @param MatchCase<T, T> ...$cases
     *
     * @return T
     */
    public function of(MatchCase ...$cases)
    {
        return GenericList::ofAll($cases)
            ->find(function ($case): bool {
                return $case->match($this->value);
            })->map(function (MatchCase $case) {
                return $case->apply($this->value);
            })->getOrElseThrow(new MatchNotFoundException());
    }
}
