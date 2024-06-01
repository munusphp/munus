<?php

declare(strict_types=1);

namespace Munus\Generators\Tuple\FragmentGenerator;

use Munus\Generators\Tuple\FragmentGenerator;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

class ToArrayMethodGenerator extends FragmentGenerator
{
    public function append(PhpNamespace $namespace, ClassType $class, int $tupleSize, int $maxTupleSize): void
    {
        $toArray = $class->addMethod('toArray');
        if ($this->isTupleZero($tupleSize)) {
            $toArray->addComment('@return array{}');
        } else {
            $toArray->addComment('@return array{'.join(', ', array_map(fn ($int) => 'T'.$int, range(1, $tupleSize))).'}');
        }
        $toArray->setReturnType('array');

        if ($this->isTupleZero($tupleSize)) {
            $toArray->setBody('return [];');

            return;
        }

        $toArray->addBody('return [');

        foreach ($this->classValues($tupleSize) as $classValue) {
            $toArray->addBody(sprintf('    %s,', $classValue));
        }

        $toArray->addBody('];');
    }
}
