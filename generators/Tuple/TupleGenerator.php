<?php

declare(strict_types=1);

namespace Munus\Generators\Tuple;

use Munus\Tuple;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;

class TupleGenerator
{
    public const DIRECTORY_NAME = 'src/Tuple';
    public const TEMP_DIRECTORY_NAME = '.tuple';
    public const TUPLE_NAMESPACE = 'Munus\Tuple';
    public const FILE_COMMENT_FIRST_LINE = 'This class is generated using generate-tuples script.';
    public const FILE_COMMENT_SECOND_LINE = 'Do not change it manually! Modify generator and use above script.';

    /** @var string[] */
    private array $preparedClasses = [];

    /**
     * @param FragmentGenerator[] $fragmentGenerators
     */
    public function __construct(
        private ClassPersister $classPersister,
        private TupleClassNameGenerator $classNameGenerator,
        private array $fragmentGenerators = [],
    ) {
    }

    public function prepareTuples(int $maxTupleSize = 8): void
    {
        $printer = new PsrPrinter();

        for ($size = 0; $size <= $maxTupleSize; ++$size) {
            $className = $this->classNameGenerator->forSize(self::TUPLE_NAMESPACE, $size);
            $file = $this->generateTupleClassFile($size, $maxTupleSize);
            $class = $file->getClasses()[$className];

            $this->classPersister->save(
                self::TEMP_DIRECTORY_NAME,
                $class->getName(),
                $printer->printFile($file),
            );

            $this->preparedClasses[] = $class->getName();
        }
    }

    /**
     * @return string[]
     */
    public function getPreparedTuplesNames(): array
    {
        return $this->preparedClasses;
    }

    public function commitPreparedTuples(): void
    {
        foreach ($this->preparedClasses as $preparedClass) {
            $this->classPersister
                ->moveClass(self::TEMP_DIRECTORY_NAME, self::DIRECTORY_NAME, $preparedClass);
        }
    }

    private function generateTupleClassFile(int $size, int $maxTupleSize): PhpFile
    {
        $file = new PhpFile();
        $file->addComment(self::FILE_COMMENT_FIRST_LINE);
        $file->addComment(self::FILE_COMMENT_SECOND_LINE);
        $file->setStrictTypes();

        $namespace = $file->addNamespace(self::TUPLE_NAMESPACE);
        $namespace->addUse(Tuple::class);

        $class = $file->addClass($this->classNameGenerator->forSize(self::TUPLE_NAMESPACE, $size));
        $class->setExtends(Tuple::class);

        foreach ($this->fragmentGenerators as $fragmentGenerator) {
            $fragmentGenerator->append($namespace, $class, $size, $maxTupleSize);
        }

        return $file;
    }
}
