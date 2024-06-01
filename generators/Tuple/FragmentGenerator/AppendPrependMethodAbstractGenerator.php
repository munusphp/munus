<?php

declare(strict_types=1);

namespace Munus\Generators\Tuple\FragmentGenerator;

use Munus\Exception\UnsupportedOperationException;
use Munus\Generators\Tuple\FragmentGenerator;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

abstract class AppendPrependMethodAbstractGenerator extends FragmentGenerator
{
    public function append(PhpNamespace $namespace, ClassType $class, int $tupleSize, int $maxTupleSize): void
    {
        $resultTupleSize = $tupleSize + 1;

        $method = $class->addMethod($this->methodName());
        $method->addParameter('value');
        $method->addComment('@template T');
        $method->addComment(self::EMPTY_COMMENT_LINE);
        $method->addComment('@param T $value');
        $method->addComment(self::EMPTY_COMMENT_LINE);

        if ($this->isMaxSizeTuple($tupleSize, $maxTupleSize)) {
            $namespace->addUse(UnsupportedOperationException::class);
            $method->setBody($this->getMaxTupleSizeExceptionThrowBody());
            $method->setReturnType('never');

            return;
        }

        $method->setReturnType($this->getMethodReturnType($namespace, $resultTupleSize));
        $method->addComment($this->getReturnTypeComment($resultTupleSize, $tupleSize));
        $method->setBody($this->getMethodBody($resultTupleSize, $tupleSize));
    }

    private function getMaxTupleSizeExceptionThrowBody(): string
    {
        return sprintf(
            'throw new UnsupportedOperationException(\'Can\\\'t %s next value. This is biggest possible Tuple\');',
            $this->methodName(),
        );
    }

    private function getMethodReturnType(PhpNamespace $namespace, int $resultTupleSize): string
    {
        return sprintf('%s\Tuple%s', $namespace->getName(), $resultTupleSize);
    }

    private function getReturnTypeComment(int $resultTupleSize, int $tupleSize): string
    {
        return sprintf('@return Tuple%s<%s>', $resultTupleSize, $this->listOfTypes($tupleSize));
    }

    private function getMethodBody(int $resultTupleSize, int $tupleSize): string
    {
        return sprintf('return new Tuple%s(%s);', $resultTupleSize, $this->listOfValues($tupleSize));
    }

    abstract protected function listOfValues(int $tupleSize): string;

    abstract protected function listOfTypes(int $tupleSize): string;

    abstract protected function methodName(): string;
}
