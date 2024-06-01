<?php

declare(strict_types=1);

namespace Munus\Generators\Tuple\FragmentGenerator;

use Munus\Generators\Tuple\FragmentGenerator;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

class ConcatMethodGenerator extends FragmentGenerator
{
    public function append(PhpNamespace $namespace, ClassType $class, int $tupleSize, int $maxTupleSize): void
    {
        $concatableSize = $maxTupleSize - $tupleSize;

        foreach (range(0, $concatableSize) as $n) {
            $this->generateConcatTupleNMethod($n, $tupleSize, $class);
        }
    }

    private function generateConcatTupleNMethod(int $n, int $tupleSize, ClassType $class): void
    {
        $returnTupleSize = $tupleSize + $n;

        $concatTupleN = $class->addMethod(sprintf('concatTuple%s', $n));
        $concatTupleN->addParameter('tuple');

        $types = $this->types($tupleSize);
        $uTypes = $this->listOfTemplate('U%s', $n);
        $bothTypes = [...$types, ...$uTypes];

        $paramTupleGenerics = 0 == $n
            ? ''
            : sprintf('<%s>', join(', ', $uTypes));
        $returnTupleGenerics = 0 == $returnTupleSize
            ? ''
            : sprintf('<%s>', join(', ', $bothTypes));

        foreach ($uTypes as $uType) {
            $concatTupleN->addComment(sprintf('@template %s', $uType));
        }

        if (0 !== count($uTypes)) {
            $concatTupleN->addComment(self::EMPTY_COMMENT_LINE);
        }

        $concatTupleN->addComment(sprintf('@param Tuple%s%s $tuple', $n, $paramTupleGenerics));
        $concatTupleN->addComment(self::EMPTY_COMMENT_LINE);
        $concatTupleN->addComment(sprintf('@return Tuple%s%s', $returnTupleSize, $returnTupleGenerics));

        if ($this->isTupleZero($tupleSize) && $n === 0) {
            $concatTupleN->addBody('return new Tuple0();');
        } else {
            $concatTupleN->addBody('return $this->concat($tuple);');
        }
    }
}
