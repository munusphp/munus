<?php

declare(strict_types=1);

namespace Munus\Generators\Tuple;

class FilePutContentsClassPersister implements ClassPersister
{
    public function __construct(private string $sourcePath)
    {
    }

    public function save(string $directory, string $className, string $content)
    {
        $filePath = $this->sourcePath.$directory.'/'.$className.'.php';
        file_put_contents($filePath, $content);
    }
}
