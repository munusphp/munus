<?php

declare(strict_types=1);

namespace Munus\Generators\Tuple\FragmentGenerator;

use Munus\Generators\Tuple\FragmentGenerator;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

class ConstructorMethodGenerator extends FragmentGenerator
{
    public function append(PhpNamespace $namespace, ClassType $class, int $tupleSize, int $maxTupleSize): void
    {
        $constructor = $class->addMethod('__construct');

        foreach ($this->parameterNames($tupleSize) as $n => $parameterName) {
            $constructor->addComment(sprintf('@param T%s $%s', $n + 1, $parameterName));
            $constructor->addPromotedParameter($parameterName)->setPrivate();
        }
    }
}
