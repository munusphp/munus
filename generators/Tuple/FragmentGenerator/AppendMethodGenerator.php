<?php

declare(strict_types=1);

namespace Munus\Generators\Tuple\FragmentGenerator;

class AppendMethodGenerator extends AppendPrependMethodAbstractGenerator
{
    protected function methodName(): string
    {
        return 'append';
    }

    protected function listOfTypes(int $tupleSize): string
    {
        return join(', ', [...$this->types($tupleSize), 'T']);
    }

    protected function listOfValues(int $tupleSize): string
    {
        return join(', ', [...$this->classValues($tupleSize), '$value']);
    }
}
