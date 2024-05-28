<?php

declare(strict_types=1);

namespace Munus\Generators\Tuple;

class TupleGeneratorBuilder
{
    /** @var FragmentGenerator[] */
    private array $fragmentGenerators = [];

    public function __construct(
        private ClassPersister $classPersister,
        private TupleClassNameGenerator $classNameGenerator,
    ) {
    }

    public function getTupleGenerator(): TupleGenerator
    {
        return new TupleGenerator($this->classPersister, $this->classNameGenerator, $this->fragmentGenerators);
    }

    public function appendFragmentGenerator(FragmentGenerator $fragmentGenerator): self
    {
        $this->fragmentGenerators[] = $fragmentGenerator;

        return $this;
    }
}
