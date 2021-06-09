<?php

declare(strict_types=1);

namespace Munus;

use Munus\Collection\GenericList;
use Munus\Exception\MatchNotFoundException;
use Munus\Exception\MultipleDefaultCasesException;
use Munus\Match\DefaultCase;
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
     * @throws MultipleDefaultCasesException|MatchNotFoundException
     *
     * @return T
     */
    public function of(MatchCase ...$cases)
    {
        $casesList = GenericList::ofAll($cases);
        $this->checkDefaultCases($casesList);

        return $casesList
            ->find(function (MatchCase $case): bool {
                return $case->match($this->value);
            })->map(function (MatchCase $case) {
                return $case->apply($this->value);
            })->getOrElseThrow(new MatchNotFoundException());
    }

    /**
     * @param GenericList<MatchCase> $casesList
     *
     * @throws MultipleDefaultCasesException
     */
    private function checkDefaultCases(GenericList $casesList): void
    {
        $defaultCases = $casesList
            ->filter(function (MatchCase $case): bool {
                return $case instanceof DefaultCase;
            })->length();

        if ($defaultCases > 1) {
            throw new MultipleDefaultCasesException('Match expression can contain only one default case');
        }
    }
}
