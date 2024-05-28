<?php

declare(strict_types=1);

namespace Munus\Generators\Tuple;

use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

abstract class FragmentGenerator
{
    private const TYPE_TEMPLATE = 'T%s';
    private const VALUE_TEMPLATE = '$value%s';
    private const CLASS_VALUE_TEMPLATE = '$this->value%s';
    private const PARAMETER_NAMES_TEMPLATE = 'value%s';
    protected const EMPTY_COMMENT_LINE = '';

    abstract public function append(PhpNamespace $namespace, ClassType $class, int $tupleSize, int $maxTupleSize): void;

    protected function isMaxSizeTuple(int $tupleSize, int $maxTupleSize): bool
    {
        return $tupleSize === $maxTupleSize;
    }

    protected function isTupleZero(int $tupleSize): bool
    {
        return 0 === $tupleSize;
    }

    /**
     * @return string[]
     */
    protected function types(int $tupleSize): array
    {
        return $this->listOfTemplate(self::TYPE_TEMPLATE, $tupleSize);
    }

    /**
     * @return string[]
     */
    protected function values(int $tupleSize): array
    {
        return $this->listOfTemplate(self::VALUE_TEMPLATE, $tupleSize);
    }

    /**
     * @return string[]
     */
    protected function classValues(int $tupleSize): array
    {
        return $this->listOfTemplate(self::CLASS_VALUE_TEMPLATE, $tupleSize);
    }

    /**
     * @return string[]
     */
    protected function parameterNames(int $tupleSize): array
    {
        return $this->listOfTemplate(self::PARAMETER_NAMES_TEMPLATE, $tupleSize);
    }

    /**
     * @return string[]
     */
    protected function listOfTemplate(string $template, int $tupleSize): array
    {
        if ($this->isTupleZero($tupleSize)) {
            return [];
        }

        return array_map(
            fn (int $n): string => sprintf($template, $n),
            range(1, $tupleSize),
        );
    }
}
