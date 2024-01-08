<?php

declare(strict_types=1);

namespace Munus\Generators\Tuple;

use Munus\Generators\Tuple\FragmentGenerator\AppendMethodGenerator;
use Munus\Generators\Tuple\FragmentGenerator\ArityMethodGenerator;
use Munus\Generators\Tuple\FragmentGenerator\ConcatMethodGenerator;
use Munus\Generators\Tuple\FragmentGenerator\ConstructorMethodGenerator;
use Munus\Generators\Tuple\FragmentGenerator\GenericTypesGenerator;
use Munus\Generators\Tuple\FragmentGenerator\PrependMethodGenerator;
use Munus\Generators\Tuple\FragmentGenerator\ToArrayMethodGenerator;

class TupleGeneratorConfiguration
{
    public const DEFAULT_SOURCE_PATH = __DIR__.'/../../';

    public static function getTupleGenerator(
        ClassPersister $classPersister = null,
        string $sourcePath = null,
    ): TupleGenerator {
        if (null === $classPersister) {
            $classPersister = new FilePutContentsClassPersister($sourcePath ?? self::DEFAULT_SOURCE_PATH);
        }

        $classNameGenerator = new TupleClassNameGenerator();

        $tupleGeneratorBuilder = new TupleGeneratorBuilder($classPersister, $classNameGenerator);
        $tupleGeneratorBuilder->appendFragmentGenerator(new GenericTypesGenerator());
        $tupleGeneratorBuilder->appendFragmentGenerator(new ConstructorMethodGenerator());
        $tupleGeneratorBuilder->appendFragmentGenerator(new ArityMethodGenerator());
        $tupleGeneratorBuilder->appendFragmentGenerator(new ToArrayMethodGenerator());
        $tupleGeneratorBuilder->appendFragmentGenerator(new PrependMethodGenerator());
        $tupleGeneratorBuilder->appendFragmentGenerator(new AppendMethodGenerator());
        $tupleGeneratorBuilder->appendFragmentGenerator(new ConcatMethodGenerator());

        return $tupleGeneratorBuilder->getTupleGenerator();
    }
}
