<?php

declare(strict_types=1);

namespace Munus\Generators\Tuple\FragmentGenerator;

class PrependMethodGenerator extends AppendPrependMethodAbstractGenerator
{
    protected function methodName(): string
    {
        return 'prepend';
    }

    protected function listOfValues(int $tupleSize): string
    {
        return join(', ', ['$value', ...$this->classValues($tupleSize)]);
    }

    protected function listOfTypes(int $tupleSize): string
    {
        return join(', ', ['T', ...$this->types($tupleSize)]);
    }
}
