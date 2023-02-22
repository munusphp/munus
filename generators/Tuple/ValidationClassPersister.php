<?php

declare(strict_types=1);

namespace Munus\Generators\Tuple;

class ValidationClassPersister implements ClassPersister
{
    private bool $foundDiff = false;

    public function __construct(private string $sourcePath)
    {
    }

    public function save(string $directory, string $className, string $content)
    {
        $filePath = $this->sourcePath.$directory.'/'.$className.'.php';

        if (file_get_contents($filePath) !== $content) {
            $this->foundDiff = true;
        }
    }

    public function foundDiff(): bool
    {
        return $this->foundDiff;
    }
}
