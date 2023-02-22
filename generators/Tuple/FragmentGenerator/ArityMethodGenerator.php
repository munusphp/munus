<?php

declare(strict_types=1);

namespace Munus\Generators\Tuple\FragmentGenerator;

use Munus\Generators\Tuple\FragmentGenerator;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

class ArityMethodGenerator extends FragmentGenerator
{
    public function append(PhpNamespace $namespace, ClassType $class, int $tupleSize, int $maxTupleSize): void
    {
        $const = $class
            ->addConstant('SIZE', $tupleSize)
            ->setPrivate();

        $arity = $class->addMethod('arity');
        $arity->setReturnType('int');
        $arity->setBody(sprintf('return self::%s;', $const->getName()));
    }
}
