<?php

declare(strict_types=1);

namespace Munus\Tests\Generators\Tuple\Fixtures;

use Munus\Generators\Tuple\ClassPersister;

class FakeClassPersister implements ClassPersister
{
    /** @var array{string, string, string}[] */
    private array $files;

    public function save(string $directory, string $className, string $content)
    {
        $this->files[] = [$directory, $className, $content];
    }

    /**
     * @return array{string, string, string}
     */
    public function getFileSavedFromBeginning(int $fileNumber): array
    {
        return $this->files[$fileNumber - 1];
    }
}
